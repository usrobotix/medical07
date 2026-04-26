<?php

namespace App\Jobs;

use App\Models\AuditEvent;
use App\Models\Backup;
use App\Services\RestoreProgressService;
use App\Services\YandexDiskService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PharData;
use ZipArchive;

class RestoreDatabaseFromBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries = 1;

    /** Read buffer size for streaming the SQL file line-by-line (bytes). */
    private const SQL_READ_BUFFER = 8192;

    public function __construct(
        public readonly int $sourceBackupId,
        public readonly int $restoreRecordId,
        public readonly string $restoreUuid = '',
    ) {}

    public function handle(YandexDiskService $yandex): void
    {
        $progress = new RestoreProgressService();
        $hasUuid  = $this->restoreUuid !== '';

        $sourceBackup  = Backup::findOrFail($this->sourceBackupId);
        $restoreRecord = Backup::findOrFail($this->restoreRecordId);

        $this->dbUpdate($restoreRecord, [
            'status'           => 'running',
            'started_at'       => now(),
            'progress_percent' => 5,
            'current_step'     => 'snapshot',
        ]);

        if ($hasUuid) {
            $progress->write($this->restoreUuid, [
                'status'           => 'running',
                'progress_percent' => 5,
                'current_step'     => 'snapshot',
                'error_message'    => null,
                'finished_at'      => null,
            ]);
        }

        $tmpDir = null;

        try {
            // ── Step 1: Safety snapshot ─────────────────────────────────────────
            $this->createSafetySnapshot($restoreRecord, $yandex);
            $this->dbUpdate($restoreRecord, ['progress_percent' => 20, 'current_step' => 'download']);
            if ($hasUuid) {
                $progress->write($this->restoreUuid, [
                    'status'           => 'running',
                    'progress_percent' => 20,
                    'current_step'     => 'download',
                    'error_message'    => null,
                    'finished_at'      => null,
                ]);
            }

            // ── Step 2: Resolve archive path ────────────────────────────────────
            $archivePath = $this->resolveArchivePath($sourceBackup, $yandex);
            $this->dbUpdate($restoreRecord, ['progress_percent' => 30, 'current_step' => 'extract']);
            if ($hasUuid) {
                $progress->write($this->restoreUuid, [
                    'status'           => 'running',
                    'progress_percent' => 30,
                    'current_step'     => 'extract',
                    'error_message'    => null,
                    'finished_at'      => null,
                ]);
            }

            // ── Step 3: Extract SQL from archive ────────────────────────────────
            $tmpDir  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'restore_' . $this->restoreRecordId . '_' . uniqid();
            @mkdir($tmpDir, 0755, true);

            $sqlPath = $this->extractSql($archivePath, $tmpDir);
            $this->dbUpdate($restoreRecord, ['progress_percent' => 35, 'current_step' => 'restore_db']);
            if ($hasUuid) {
                $progress->write($this->restoreUuid, [
                    'status'           => 'running',
                    'progress_percent' => 35,
                    'current_step'     => 'restore_db',
                    'error_message'    => null,
                    'finished_at'      => null,
                ]);
            }

            // ── Step 4: Execute SQL via PDO ─────────────────────────────────────
            // NOTE: During SQL execution the backups table is dropped and recreated,
            // so DB-based progress updates may fail after that point.
            // Filesystem-based progress (via restoreUuid) is the reliable fallback.
            $this->executeSqlFile($sqlPath, $restoreRecord, $this->restoreUuid, $progress);

            // Cleanup temp files
            @unlink($sqlPath);
            $this->removeDir($tmpDir);

            $finishedAt = now()->toIso8601String();

            if ($hasUuid) {
                $progress->write($this->restoreUuid, [
                    'status'           => 'done',
                    'progress_percent' => 100,
                    'current_step'     => 'done',
                    'error_message'    => null,
                    'finished_at'      => $finishedAt,
                ]);
                // Purge stale progress files from previous restores
                $progress->purgeOld(48);
            }

            $this->dbUpdate($restoreRecord, [
                'status'           => 'done',
                'progress_percent' => 100,
                'current_step'     => 'done',
                'finished_at'      => now(),
            ]);

            try {
                AuditEvent::create([
                    'user_id'     => $restoreRecord->user_id,
                    'action'      => 'backup.restored',
                    'entity_type' => 'backup',
                    'entity_id'   => $sourceBackup->id,
                    'ip'          => null,
                    'payload'     => [
                        'restore_record_id' => $restoreRecord->id,
                        'source_backup_id'  => $sourceBackup->id,
                    ],
                    'created_at'  => now(),
                ]);
            } catch (\Throwable $auditEx) {
                Log::warning('RestoreJob: could not write audit event after restore: ' . $auditEx->getMessage());
            }

        } catch (\Throwable $e) {
            Log::error('RestoreDatabaseFromBackupJob failed', [
                'source_backup_id'  => $this->sourceBackupId,
                'restore_record_id' => $this->restoreRecordId,
                'restore_uuid'      => $this->restoreUuid,
                'exception'         => $e->getMessage(),
                'trace'             => $e->getTraceAsString(),
            ]);

            if ($tmpDir) {
                $this->removeDir($tmpDir);
            }

            if ($hasUuid) {
                $progress->write($this->restoreUuid, [
                    'status'           => 'failed',
                    'progress_percent' => 0,
                    'current_step'     => 'failed',
                    'error_message'    => $e->getMessage(),
                    'finished_at'      => now()->toIso8601String(),
                ]);
            }

            $this->dbUpdate($restoreRecord, [
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'current_step'  => 'failed',
                'finished_at'   => now(),
            ]);

            try {
                AuditEvent::create([
                    'user_id'     => $restoreRecord->user_id,
                    'action'      => 'backup.restore_failed',
                    'entity_type' => 'backup',
                    'entity_id'   => $sourceBackup->id,
                    'ip'          => null,
                    'payload'     => ['error' => $e->getMessage()],
                    'created_at'  => now(),
                ]);
            } catch (\Throwable $auditEx) {
                Log::warning('RestoreJob: could not write audit event after failure: ' . $auditEx->getMessage());
            }

            throw $e;
        }
    }

    /**
     * Try to update the restore record in the DB.
     * After the SQL restore wipes and recreates the backups table, updates may fail silently.
     */
    private function dbUpdate(Backup $record, array $data): void
    {
        try {
            $record->update($data);
        } catch (\Throwable $e) {
            Log::debug('RestoreJob: DB progress update failed (expected during restore): ' . $e->getMessage());
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Create a safety DB snapshot using PDO before overwriting the current DB.
     * Throws on failure so the caller can abort.
     */
    private function createSafetySnapshot(Backup $restoreRecord, YandexDiskService $yandex): void
    {
        $snapshot = Backup::create([
            'type'             => 'db',
            'kind'             => 'safety_snapshot',
            'formats'          => ['zip'],
            'status'           => 'running',
            'initiated_by'     => 'system',
            'user_id'          => $restoreRecord->user_id,
            'progress_percent' => 0,
            'current_step'     => 'db_dump',
        ]);

        try {
            $baseDir = storage_path('app/backups');
            $dateDir = $baseDir . DIRECTORY_SEPARATOR . now()->format('Y-m-d');
            if (!is_dir($dateDir)) {
                mkdir($dateDir, 0755, true);
            }

            $timestamp = now()->format('Y-m-d_His');
            $sqlPath   = $dateDir . DIRECTORY_SEPARATOR . $timestamp . '_safety_snapshot.sql';

            $this->dumpDatabasePdo($sqlPath);

            $archivePath = $this->archiveSqlToZip($sqlPath, $dateDir, $timestamp . '_safety_snapshot_db');
            @unlink($sqlPath);

            $remotePaths = [];
            if ($yandex->isConfigured()) {
                $remotePath = $yandex->upload($archivePath, basename($archivePath));
                if ($remotePath) {
                    $remotePaths['zip'] = $remotePath;
                }
            }

            $snapshot->update([
                'status'           => 'done',
                'local_paths'      => ['zip' => $archivePath],
                'remote_paths'     => $remotePaths ?: null,
                'size_bytes'       => filesize($archivePath),
                'progress_percent' => 100,
                'current_step'     => 'done',
                'finished_at'      => now(),
            ]);

        } catch (\Throwable $e) {
            $snapshot->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'current_step'  => 'failed',
                'finished_at'   => now(),
            ]);
            throw new \RuntimeException('Safety snapshot failed — restore aborted: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Dump the current DB to a plain SQL file using PDO (no mysqldump dependency).
     */
    private function dumpDatabasePdo(string $filePath): void
    {
        $pdo    = DB::getPdo();
        $dbName = config('database.connections.mysql.database');

        $handle = fopen($filePath, 'w');
        if ($handle === false) {
            throw new \RuntimeException('Cannot open SQL dump file for writing: ' . $filePath);
        }

        fwrite($handle, "-- Safety snapshot: " . now()->toIso8601String() . "\n");
        fwrite($handle, "-- Database: {$dbName}\n\n");
        fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
        fwrite($handle, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n");

        $tables = $pdo->query("SHOW TABLES FROM `" . $this->escapeIdentifier($dbName) . "`")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $safeTable = $this->escapeIdentifier($table);
            $createRow = $pdo->query("SHOW CREATE TABLE `{$safeTable}`")->fetch(\PDO::FETCH_NUM);
            fwrite($handle, "\n-- Table: `{$safeTable}`\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$safeTable}`;\n");
            fwrite($handle, $createRow[1] . ";\n\n");

            $rows = $pdo->query("SELECT * FROM `{$safeTable}`")->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $columns = array_map(fn ($c) => '`' . $this->escapeIdentifier($c) . '`', array_keys($rows[0]));
                // Write each row as a separate INSERT to avoid extremely large statements.
                foreach ($rows as $row) {
                    $escaped = array_map(function ($v) use ($pdo) {
                        if ($v === null) return 'NULL';
                        return $pdo->quote($v);
                    }, $row);
                    fwrite($handle, "INSERT INTO `{$safeTable}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $escaped) . ");\n");
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    /**
     * Escape a MySQL identifier (table/column/database name) for use inside backtick-quoting.
     * Backticks inside names are doubled per MySQL rules.
     */
    private function escapeIdentifier(string $name): string
    {
        return str_replace('`', '``', $name);
    }

    private function archiveSqlToZip(string $sqlPath, string $outDir, string $baseName): string
    {
        $outPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.zip';
        $zip     = new ZipArchive();
        $zip->open($outPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFile($sqlPath, basename($sqlPath));
        $zip->close();
        return $outPath;
    }

    /**
     * Resolve the local archive path for the source backup.
     * Downloads from Yandex Disk if local file is missing.
     */
    private function resolveArchivePath(Backup $sourceBackup, YandexDiskService $yandex): string
    {
        $localPaths = $sourceBackup->local_paths ?? [];

        // Prefer zip, then tar_gz, then first available
        foreach (['zip', 'tar_gz'] as $fmt) {
            if (!empty($localPaths[$fmt]) && is_file($localPaths[$fmt])) {
                return $localPaths[$fmt];
            }
        }
        if (!empty($localPaths)) {
            $first = array_values($localPaths)[0];
            if (is_file($first)) return $first;
        }

        // Try Yandex Disk
        $remotePaths = $sourceBackup->remote_paths ?? [];
        foreach (['zip', 'tar_gz'] as $fmt) {
            if (!empty($remotePaths[$fmt]) && $yandex->isConfigured()) {
                $downloaded = $yandex->download($remotePaths[$fmt]);
                if ($downloaded) return $downloaded;
            }
        }
        if (!empty($remotePaths) && $yandex->isConfigured()) {
            $downloaded = $yandex->download(array_values($remotePaths)[0]);
            if ($downloaded) return $downloaded;
        }

        throw new \RuntimeException('Backup archive not found locally or on Yandex Disk.');
    }

    /**
     * Extract the first .sql file from zip or tar.gz archive.
     */
    private function extractSql(string $archivePath, string $tmpDir): string
    {
        if (str_ends_with($archivePath, '.zip')) {
            $zip = new ZipArchive();
            if ($zip->open($archivePath) !== true) {
                throw new \RuntimeException('Cannot open ZIP archive: ' . $archivePath);
            }
            $sqlEntry = null;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (str_ends_with($name, '.sql')) {
                    $sqlEntry = $name;
                    break;
                }
            }
            if ($sqlEntry === null) {
                throw new \RuntimeException('No .sql file found inside ZIP archive.');
            }
            $zip->extractTo($tmpDir, [$sqlEntry]);
            $zip->close();
            return $tmpDir . DIRECTORY_SEPARATOR . basename($sqlEntry);
        }

        if (str_ends_with($archivePath, '.tar.gz') || str_ends_with($archivePath, '.tgz')) {
            // PharData: decompress .tar.gz → .tar next to the source, then extract
            $phar    = new PharData($archivePath);
            $tarObj  = $phar->decompress();
            $tarPath = $tarObj->getPath(); // track path for cleanup

            try {
                $tarObj->extractTo($tmpDir);
            } finally {
                // Always clean up the intermediate .tar file
                if ($tarPath && is_file($tarPath)) {
                    @unlink($tarPath);
                }
            }

            // Find .sql inside extracted dir
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tmpDir));
            foreach ($it as $file) {
                if ($file->getExtension() === 'sql') {
                    return $file->getPathname();
                }
            }
            throw new \RuntimeException('No .sql file found inside tar.gz archive.');
        }

        throw new \RuntimeException('Unsupported archive format: ' . basename($archivePath));
    }

    /**
     * Execute a mysqldump-produced SQL file via PDO with progress reporting.
     *
     * LOCK TABLES / UNLOCK TABLES statements generated by mysqldump are skipped
     * entirely: executing them on a shared connection locks the MySQL session and
     * prevents progress updates to the `backups` table (SQLSTATE HY000 / 1100).
     * A dedicated PDO connection is used for SQL execution so that the Eloquent
     * connection (used for progress updates) is never affected by session-level
     * state from the dump.
     *
     * Progress is written to the filesystem via RestoreProgressService so it
     * survives the DROP/CREATE of the backups table during the restore.
     * DB-based progress updates are attempted but silently ignored on failure.
     */
    private function executeSqlFile(
        string $sqlPath,
        Backup $restoreRecord,
        string $restoreUuid,
        RestoreProgressService $progress
    ): void {
        // Use a fresh, dedicated PDO connection for restore execution so that any
        // session-level state (remaining LOCK TABLES, custom SET flags, etc.) never
        // bleeds into the Eloquent connection that performs progress updates.
        $pdo      = $this->createFreshPdo();
        $fileSize = filesize($sqlPath);

        if (!$fileSize) {
            throw new \RuntimeException('SQL file is empty: ' . $sqlPath);
        }

        $handle = @fopen($sqlPath, 'r');
        if (!$handle) {
            throw new \RuntimeException('Cannot open SQL file for reading: ' . $sqlPath);
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
        $pdo->exec("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");

        $buffer          = '';
        $bytesRead       = 0;
        $lastProgressPct = 35;
        $hasUuid         = $restoreUuid !== '';

        while (!feof($handle)) {
            $line = fgets($handle, self::SQL_READ_BUFFER);
            if ($line === false) break;

            $bytesRead += strlen($line);
            $trimmed    = ltrim($line);

            // Skip comment-only lines when buffer is empty
            if ($buffer === '' && (
                $trimmed === '' ||
                str_starts_with($trimmed, '--') ||
                str_starts_with($trimmed, '#')
            )) {
                continue;
            }

            $buffer .= $line;

            // A statement is complete when the buffer (trimmed) ends with ';'
            if (substr(rtrim($buffer), -1) === ';') {
                $stmt = trim($buffer);
                $buffer = '';

                if ($stmt !== '' && !str_starts_with($stmt, '--') && !str_starts_with($stmt, '#')) {
                    // Skip LOCK TABLES / UNLOCK TABLES produced by mysqldump.
                    // Executing them locks the MySQL session and prevents progress
                    // updates to the `backups` table (SQLSTATE HY000 / error 1100).
                    if ($this->shouldSkipStatement($stmt)) {
                        Log::debug('RestoreJob: skipping lock statement: ' . substr($stmt, 0, 120));
                    } else {
                        try {
                            $pdo->exec($stmt);
                        } catch (\PDOException $e) {
                            Log::warning('RestoreJob SQL exec warning: ' . $e->getMessage() . ' | stmt: ' . substr($stmt, 0, 120));
                        }
                    }
                }

                // Update progress: restore_db occupies 35–95%
                $filePct    = (int)(($bytesRead / $fileSize) * 100);
                $overallPct = 35 + (int)(($filePct / 100) * 60);
                if ($overallPct >= $lastProgressPct + 5) {
                    $lastProgressPct = $overallPct;
                    $pct = min($overallPct, 95);

                    // Filesystem progress is the primary mechanism (survives DB restore).
                    if ($hasUuid) {
                        $progress->write($restoreUuid, [
                            'status'           => 'running',
                            'progress_percent' => $pct,
                            'current_step'     => 'restore_db',
                            'error_message'    => null,
                            'finished_at'      => null,
                        ]);
                    }

                    // DB update is best-effort: the backups table may not exist mid-restore.
                    $this->dbUpdate($restoreRecord, ['progress_percent' => $pct]);
                }
            }
        }

        fclose($handle);

        // Execute any remaining buffered content
        $stmt = trim($buffer);
        if ($stmt !== '') {
            if ($this->shouldSkipStatement($stmt)) {
                Log::debug('RestoreJob: skipping lock statement (final buffer): ' . substr($stmt, 0, 120));
            } else {
                try {
                    $pdo->exec($stmt);
                } catch (\PDOException $e) {
                    Log::warning('RestoreJob SQL final exec warning: ' . $e->getMessage());
                }
            }
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Returns true when a SQL statement should be skipped during restore.
     *
     * mysqldump wraps each table's data block with LOCK TABLES … WRITE / UNLOCK TABLES.
     * Executing these on the restore connection locks the MySQL session and prevents
     * the Eloquent progress updates to the `backups` table, producing:
     *   SQLSTATE[HY000]: General error: 1100 Table 'backups' was not locked with LOCK TABLES
     *
     * Both upper- and lower-case variants (and any leading whitespace) are handled.
     */
    protected function shouldSkipStatement(string $stmt): bool
    {
        $upper = strtoupper(ltrim($stmt));
        return str_starts_with($upper, 'LOCK TABLES') ||
               str_starts_with($upper, 'UNLOCK TABLES');
    }

    /**
     * Create a dedicated PDO connection for SQL restore using the same credentials
     * as the application's default MySQL connection.
     *
     * Keeping the restore session isolated means any session-level state (e.g. a
     * LOCK TABLES statement that was somehow not skipped, or a non-standard SQL_MODE)
     * can never affect the Eloquent connection used for progress updates.
     *
     * SSL/TLS options and unix_socket (if configured) are forwarded so the fresh
     * connection maintains the same security posture as the application connection.
     */
    private function createFreshPdo(): \PDO
    {
        $cfg = config('database.connections.mysql');

        foreach (['host', 'database', 'username', 'password'] as $key) {
            if (!isset($cfg[$key])) {
                throw new \RuntimeException("Missing required MySQL config key: {$key}");
            }
        }

        // Build DSN — prefer unix_socket when configured, otherwise TCP host:port.
        if (!empty($cfg['unix_socket'])) {
            $dsn = sprintf(
                'mysql:unix_socket=%s;dbname=%s;charset=%s',
                $cfg['unix_socket'],
                $cfg['database'],
                $cfg['charset'] ?? 'utf8mb4',
            );
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $cfg['host'],
                $cfg['port'] ?? 3306,
                $cfg['database'],
                $cfg['charset'] ?? 'utf8mb4',
            );
        }

        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];

        // Forward SSL options when configured so the connection is as secure as
        // the application's own Eloquent connection.
        if (!empty($cfg['options'][\PDO::MYSQL_ATTR_SSL_CA])) {
            $options[\PDO::MYSQL_ATTR_SSL_CA] = $cfg['options'][\PDO::MYSQL_ATTR_SSL_CA];
        }
        if (!empty($cfg['options'][\PDO::MYSQL_ATTR_SSL_CERT])) {
            $options[\PDO::MYSQL_ATTR_SSL_CERT] = $cfg['options'][\PDO::MYSQL_ATTR_SSL_CERT];
        }
        if (!empty($cfg['options'][\PDO::MYSQL_ATTR_SSL_KEY])) {
            $options[\PDO::MYSQL_ATTR_SSL_KEY] = $cfg['options'][\PDO::MYSQL_ATTR_SSL_KEY];
        }
        if (isset($cfg['options'][\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT])) {
            $options[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] =
                $cfg['options'][\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT];
        }

        return new \PDO($dsn, $cfg['username'], $cfg['password'], $options);
    }

    private function removeDir(string $dir): void
    {
        if (!is_dir($dir)) return;
        $items = @scandir($dir);
        if ($items === false) return;
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->removeDir($path) : @unlink($path);
        }
        @rmdir($dir);
    }
}
