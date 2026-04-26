<?php

namespace App\Jobs;

use App\Models\AuditEvent;
use App\Models\Backup;
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

    public function __construct(
        public readonly int $sourceBackupId,
        public readonly int $restoreRecordId,
    ) {}

    public function handle(YandexDiskService $yandex): void
    {
        $sourceBackup  = Backup::findOrFail($this->sourceBackupId);
        $restoreRecord = Backup::findOrFail($this->restoreRecordId);

        $restoreRecord->update([
            'status'           => 'running',
            'started_at'       => now(),
            'progress_percent' => 5,
            'current_step'     => 'snapshot',
        ]);

        $tmpDir = null;

        try {
            // ── Step 1: Safety snapshot ─────────────────────────────────────────
            $this->createSafetySnapshot($restoreRecord, $yandex);
            $restoreRecord->update(['progress_percent' => 20, 'current_step' => 'download']);

            // ── Step 2: Resolve archive path ────────────────────────────────────
            $archivePath = $this->resolveArchivePath($sourceBackup, $yandex);
            $restoreRecord->update(['progress_percent' => 30, 'current_step' => 'extract']);

            // ── Step 3: Extract SQL from archive ────────────────────────────────
            $tmpDir  = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'restore_' . $this->restoreRecordId . '_' . uniqid();
            @mkdir($tmpDir, 0755, true);

            $sqlPath = $this->extractSql($archivePath, $tmpDir);
            $restoreRecord->update(['progress_percent' => 35, 'current_step' => 'restore_db']);

            // ── Step 4: Execute SQL via PDO ─────────────────────────────────────
            $this->executeSqlFile($sqlPath, $restoreRecord);

            // Cleanup temp files
            @unlink($sqlPath);
            $this->removeDir($tmpDir);

            $restoreRecord->update([
                'status'           => 'done',
                'progress_percent' => 100,
                'current_step'     => 'done',
                'finished_at'      => now(),
            ]);

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

        } catch (\Throwable $e) {
            if ($tmpDir) {
                $this->removeDir($tmpDir);
            }

            $restoreRecord->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'current_step'  => 'failed',
                'finished_at'   => now(),
            ]);

            AuditEvent::create([
                'user_id'     => $restoreRecord->user_id,
                'action'      => 'backup.restore_failed',
                'entity_type' => 'backup',
                'entity_id'   => $sourceBackup->id,
                'ip'          => null,
                'payload'     => ['error' => $e->getMessage()],
                'created_at'  => now(),
            ]);

            throw $e;
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

        $tables = $pdo->query("SHOW TABLES FROM `{$dbName}`")->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $createRow = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_NUM);
            fwrite($handle, "\n-- Table: `{$table}`\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
            fwrite($handle, $createRow[1] . ";\n\n");

            $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $columns = array_map(fn ($c) => "`{$c}`", array_keys($rows[0]));
                fwrite($handle, "INSERT INTO `{$table}` (" . implode(', ', $columns) . ") VALUES\n");
                $rowValues = [];
                foreach ($rows as $row) {
                    $escaped = array_map(function ($v) use ($pdo) {
                        if ($v === null) return 'NULL';
                        return $pdo->quote($v);
                    }, $row);
                    $rowValues[] = '(' . implode(', ', $escaped) . ')';
                }
                fwrite($handle, implode(",\n", $rowValues) . ";\n\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
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
            // PharData: decompress .tar.gz → .tar, then extract
            $phar   = new PharData($archivePath);
            $tarObj = $phar->decompress(); // creates .tar next to source file
            $tarObj->extractTo($tmpDir);

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
     */
    private function executeSqlFile(string $sqlPath, Backup $restoreRecord): void
    {
        $pdo      = DB::getPdo();
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

        while (!feof($handle)) {
            $line = fgets($handle, 8192);
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
                    try {
                        $pdo->exec($stmt);
                    } catch (\PDOException $e) {
                        Log::warning('RestoreJob SQL exec warning: ' . $e->getMessage() . ' | stmt: ' . substr($stmt, 0, 120));
                    }
                }

                // Update progress: restore_db occupies 35–95%
                $filePct     = (int)(($bytesRead / $fileSize) * 100);
                $overallPct  = 35 + (int)(($filePct / 100) * 60);
                if ($overallPct >= $lastProgressPct + 5) {
                    $lastProgressPct = $overallPct;
                    $restoreRecord->update(['progress_percent' => min($overallPct, 95)]);
                }
            }
        }

        fclose($handle);

        // Execute any remaining buffered content
        $stmt = trim($buffer);
        if ($stmt !== '') {
            try {
                $pdo->exec($stmt);
            } catch (\PDOException $e) {
                Log::warning('RestoreJob SQL final exec warning: ' . $e->getMessage());
            }
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
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
