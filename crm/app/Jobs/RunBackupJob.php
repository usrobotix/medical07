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
use ZipArchive;

class RunBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries = 1;

    public function __construct(
        public readonly int $backupId,
        public readonly bool $uploadYandex = true,
    ) {}

    public function handle(YandexDiskService $yandex): void
    {
        $backup = Backup::findOrFail($this->backupId);

        $backup->update([
            'status'     => 'running',
            'started_at' => now(),
            'progress_percent' => 0,
            'current_step'     => 'prepare',
        ]);

        try {
            $type    = $backup->type;
            $preset  = $backup->file_preset ?? 'project';
            $formats = $backup->formats ?? ['zip'];

            $baseDir = storage_path('app/backups');
            $dateDir = $baseDir . DIRECTORY_SEPARATOR . now()->format('Y-m-d');
            if (!is_dir($dateDir)) {
                mkdir($dateDir, 0755, true);
            }

            $timestamp  = now()->format('Y-m-d_His');
            $localPaths = [];
            $totalSize  = 0;

            // ── Step: prepare (5%) ──────────────────────────────────────────
            $backup->update(['progress_percent' => 5, 'current_step' => 'prepare']);

            // ── Step: db dump (→ 35%) ───────────────────────────────────────
            if (in_array($type, ['db', 'full'])) {
                $backup->update(['progress_percent' => 10, 'current_step' => 'db_dump']);

                $sqlPath = $this->dumpDatabase($dateDir, $timestamp);
                if (!$sqlPath) {
                    throw new \RuntimeException('DB dump failed');
                }

                foreach ($formats as $fmt) {
                    $archivePath          = $this->archiveFile($sqlPath, $dateDir, $timestamp . '_db', $fmt);
                    $localPaths[$fmt]     = $archivePath;
                    $totalSize           += filesize($archivePath);
                }
                @unlink($sqlPath);
            }

            $backup->update(['progress_percent' => 35, 'current_step' => 'db_dump']);

            // ── Step: file archive (→ 80%) ──────────────────────────────────
            if (in_array($type, ['files', 'full'])) {
                $backup->update(['progress_percent' => 40, 'current_step' => 'file_archive']);

                $sourceDir   = $this->getPresetPath($preset);
                $excludes    = $this->getPresetExcludes($preset);
                $archiveName = $timestamp . '_' . $type . '_' . $preset;

                foreach ($formats as $i => $fmt) {
                    $archivePath = $this->archiveDirectory(
                        $sourceDir, $excludes, $dateDir, $archiveName, $fmt, $backup
                    );
                    $key = ($type === 'full') ? ($fmt . '_files') : $fmt;
                    $localPaths[$key] = $archivePath;
                    $totalSize       += filesize($archivePath);
                }
            }

            $backup->update(['progress_percent' => 80, 'current_step' => 'file_archive']);

            // ── Step: yandex upload (→ 95%) ─────────────────────────────────
            $remotePaths = [];
            if ($this->uploadYandex && $yandex->isConfigured()) {
                $backup->update(['progress_percent' => 82, 'current_step' => 'yandex_upload']);

                $total = count($localPaths);
                $done  = 0;
                foreach ($localPaths as $key => $localPath) {
                    $remoteName = basename($localPath);
                    $remotePath = $yandex->upload($localPath, $remoteName);
                    if ($remotePath) {
                        $remotePaths[$key] = $remotePath;
                    }
                    $done++;
                    $pct = 82 + (int)(($done / max($total, 1)) * 13); // 82–95
                    $backup->update(['progress_percent' => $pct]);
                }
            }

            $backup->update(['progress_percent' => 95, 'current_step' => 'yandex_upload']);

            // ── Step: finalize (100%) ───────────────────────────────────────
            $backup->update(['progress_percent' => 98, 'current_step' => 'finalize']);

            $backup->update([
                'status'           => 'done',
                'local_paths'      => $localPaths,
                'remote_paths'     => $remotePaths ?: null,
                'size_bytes'       => $totalSize,
                'progress_percent' => 100,
                'current_step'     => 'done',
                'finished_at'      => now(),
            ]);

            AuditEvent::create([
                'user_id'     => $backup->user_id,
                'action'      => 'backup.created',
                'entity_type' => 'backup',
                'entity_id'   => $backup->id,
                'ip'          => null,
                'payload'     => [
                    'type'         => $type,
                    'preset'       => $preset,
                    'formats'      => $formats,
                    'initiated_by' => $backup->initiated_by,
                ],
                'created_at' => now(),
            ]);

        } catch (\Throwable $e) {
            $backup->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'current_step'  => 'failed',
                'finished_at'   => now(),
            ]);

            AuditEvent::create([
                'user_id'     => $backup->user_id,
                'action'      => 'backup.failed',
                'entity_type' => 'backup',
                'entity_id'   => $backup->id,
                'ip'          => null,
                'payload'     => ['error' => $e->getMessage()],
                'created_at'  => now(),
            ]);

            throw $e;
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function dumpDatabase(string $dir, string $timestamp): ?string
    {
        $config    = config('database.connections.mysql');
        $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');
        $filePath  = $dir . DIRECTORY_SEPARATOR . $timestamp . '_db.sql';

        $host   = $config['host'];
        $port   = (int)($config['port'] ?? 3306);
        $user   = $config['username'];
        $pass   = $config['password'];
        $dbname = $config['database'];

        $optFile    = $dir . DIRECTORY_SEPARATOR . '.my_' . uniqid() . '.cnf';
        $optContent = "[client]\nhost={$host}\nport={$port}\nuser={$user}\n";
        if (!empty($pass)) {
            $optContent .= "password={$pass}\n";
        }
        file_put_contents($optFile, $optContent);
        chmod($optFile, 0600);

        $cmd = sprintf(
            '%s --defaults-extra-file=%s %s > %s 2>&1',
            escapeshellarg($mysqldump),
            escapeshellarg($optFile),
            escapeshellarg($dbname),
            escapeshellarg($filePath)
        );

        exec($cmd, $output, $returnCode);
        @unlink($optFile);

        if ($returnCode !== 0) {
            throw new \RuntimeException('mysqldump failed: ' . implode("\n", $output));
        }
        return $filePath;
    }

    private function getPresetPath(string $preset): string
    {
        return match ($preset) {
            'storage_app' => storage_path('app'),
            default       => base_path(),
        };
    }

    private function getPresetExcludes(string $preset): array
    {
        if ($preset === 'storage_app') {
            return ['backups'];
        }
        return [
            'vendor',
            'node_modules',
            '.git',
            'storage/logs',
            'storage/app/backups',
            'bootstrap/cache',
        ];
    }

    private function archiveFile(string $filePath, string $outDir, string $baseName, string $fmt): string
    {
        if ($fmt === 'zip') {
            $outPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.zip';
            $zip     = new ZipArchive();
            $zip->open($outPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile($filePath, basename($filePath));
            $zip->close();
            return $outPath;
        }

        $outPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.tar.gz';
        $cmd     = sprintf(
            'tar -czf %s -C %s %s 2>&1',
            escapeshellarg($outPath),
            escapeshellarg(dirname($filePath)),
            escapeshellarg(basename($filePath))
        );
        exec($cmd, $output, $code);
        if ($code !== 0) {
            throw new \RuntimeException('tar failed: ' . implode("\n", $output));
        }
        return $outPath;
    }

    private function archiveDirectory(
        string $sourceDir,
        array $excludes,
        string $outDir,
        string $baseName,
        string $fmt,
        Backup $backup
    ): string {
        if ($fmt === 'zip') {
            $outPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.zip';
            $zip     = new ZipArchive();
            $zip->open($outPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            // Count files first for progress granularity
            $totalFiles     = $this->countFiles($sourceDir, $excludes);
            $processedFiles = 0;
            $lastPct        = $backup->progress_percent ?? 40;

            $this->addDirectoryToZip($zip, $sourceDir, $excludes, $sourceDir, $totalFiles, $processedFiles, $lastPct, $backup);
            $zip->close();
            return $outPath;
        }

        $outPath     = $outDir . DIRECTORY_SEPARATOR . $baseName . '.tar.gz';
        $excludeArgs = implode(' ', array_map(
            fn ($e) => '--exclude=' . escapeshellarg($e),
            $excludes
        ));
        $cmd = sprintf(
            'tar -czf %s %s -C %s . 2>&1',
            escapeshellarg($outPath),
            $excludeArgs,
            escapeshellarg($sourceDir)
        );
        exec($cmd, $output, $code);
        if ($code !== 0) {
            throw new \RuntimeException('tar failed: ' . implode("\n", $output));
        }
        return $outPath;
    }

    private function countFiles(string $dir, array $excludes): int
    {
        $count = 0;
        try {
            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($it as $file) {
                $relative = ltrim(str_replace($dir, '', $file->getPathname()), DIRECTORY_SEPARATOR . '/');
                foreach ($excludes as $ex) {
                    $exNorm = str_replace('/', DIRECTORY_SEPARATOR, $ex);
                    if ($relative === $exNorm || str_starts_with($relative, $exNorm . DIRECTORY_SEPARATOR)) {
                        continue 2;
                    }
                }
                if ($file->isFile()) {
                    $count++;
                }
            }
        } catch (\Throwable) {
            // If we can't count, return 0 (progress will just not update)
        }
        return $count;
    }

    private function addDirectoryToZip(
        ZipArchive $zip,
        string $baseDir,
        array $excludes,
        string $currentDir,
        int $totalFiles,
        int &$processedFiles,
        int &$lastPct,
        Backup $backup,
        int $updateEvery = 100
    ): void {
        $items = @scandir($currentDir);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $fullPath     = $currentDir . DIRECTORY_SEPARATOR . $item;
            $relativePath = ltrim(str_replace($baseDir, '', $fullPath), DIRECTORY_SEPARATOR . '/');

            foreach ($excludes as $exclude) {
                $excludeNorm = str_replace('/', DIRECTORY_SEPARATOR, $exclude);
                if ($relativePath === $excludeNorm || str_starts_with($relativePath, $excludeNorm . DIRECTORY_SEPARATOR)) {
                    continue 2;
                }
            }

            if (is_dir($fullPath)) {
                $zip->addEmptyDir($relativePath);
                $this->addDirectoryToZip($zip, $baseDir, $excludes, $fullPath, $totalFiles, $processedFiles, $lastPct, $backup, $updateEvery);
            } else {
                $zip->addFile($fullPath, $relativePath);
                $processedFiles++;

                // Update progress every N files (40% → 80% range for file archive)
                if ($totalFiles > 0 && $processedFiles % $updateEvery === 0) {
                    $pct = 40 + (int)(($processedFiles / $totalFiles) * 40);
                    if ($pct > $lastPct) {
                        $lastPct = $pct;
                        $backup->update(['progress_percent' => min($pct, 79)]);
                    }
                }
            }
        }
    }
}
