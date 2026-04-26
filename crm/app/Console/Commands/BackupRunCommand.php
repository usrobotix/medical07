<?php
namespace App\Console\Commands;

use App\Models\Backup;
use App\Models\AuditEvent;
use App\Services\YandexDiskService;
use Illuminate\Console\Command;
use ZipArchive;

class BackupRunCommand extends Command
{
    protected $signature = 'backup:run
        {--type=full : db, files, or full}
        {--preset=project : project, storage_app (for files type)}
        {--formats=zip,tar_gz : comma-separated: zip,tar_gz}
        {--yandex=1 : Upload to Yandex Disk (1/0)}
        {--initiated-by=cron : user or cron}
        {--user-id= : user ID if initiated by user}';

    protected $description = 'Run a backup (db/files/full)';

    public function handle(YandexDiskService $yandex): int
    {
        $type = $this->option('type');
        $preset = $this->option('preset');
        $formats = array_filter(explode(',', $this->option('formats')));
        $uploadYandex = (bool)$this->option('yandex');
        $initiatedBy = $this->option('initiated-by');
        $userId = $this->option('user-id') ?: null;

        $backup = Backup::create([
            'type' => $type,
            'file_preset' => in_array($type, ['files','full']) ? $preset : null,
            'formats' => $formats,
            'local_paths' => null,
            'remote_paths' => null,
            'size_bytes' => 0,
            'status' => 'running',
            'initiated_by' => $initiatedBy,
            'user_id' => $userId,
        ]);

        try {
            $baseDir = storage_path('app/backups');
            $dateDir = $baseDir . DIRECTORY_SEPARATOR . now()->format('Y-m-d');
            if (!is_dir($dateDir)) mkdir($dateDir, 0755, true);

            $timestamp = now()->format('Y-m-d_His');
            $localPaths = [];
            $totalSize = 0;

            if (in_array($type, ['db', 'full'])) {
                $sqlPath = $this->dumpDatabase($dateDir, $timestamp);
                if (!$sqlPath) throw new \RuntimeException('DB dump failed');

                foreach ($formats as $fmt) {
                    $archivePath = $this->archiveFile($sqlPath, $dateDir, $timestamp . '_db', $fmt);
                    $localPaths[$fmt] = $archivePath;
                    $totalSize += filesize($archivePath);
                }
                @unlink($sqlPath);
            }

            if (in_array($type, ['files', 'full'])) {
                $sourceDir = $this->getPresetPath($preset);
                $excludes = $this->getPresetExcludes($preset);
                $archiveName = $timestamp . '_' . $type . '_' . $preset;

                foreach ($formats as $fmt) {
                    $archivePath = $this->archiveDirectory($sourceDir, $excludes, $dateDir, $archiveName, $fmt);
                    if ($type === 'full') {
                        $localPaths[$fmt . '_files'] = $archivePath;
                    } else {
                        $localPaths[$fmt] = $archivePath;
                    }
                    $totalSize += filesize($archivePath);
                }
            }

            $remotePaths = [];
            if ($uploadYandex && $yandex->isConfigured()) {
                foreach ($localPaths as $key => $localPath) {
                    $remoteName = basename($localPath);
                    $remotePath = $yandex->upload($localPath, $remoteName);
                    if ($remotePath) $remotePaths[$key] = $remotePath;
                }
            }

            $backup->update([
                'status' => 'done',
                'local_paths' => $localPaths,
                'remote_paths' => $remotePaths ?: null,
                'size_bytes' => $totalSize,
            ]);

            AuditEvent::create([
                'user_id' => $userId,
                'action' => 'backup.created',
                'entity_type' => 'backup',
                'entity_id' => $backup->id,
                'ip' => null,
                'payload' => ['type' => $type, 'preset' => $preset, 'formats' => $formats, 'initiated_by' => $initiatedBy],
                'created_at' => now(),
            ]);

            $this->info("Backup #{$backup->id} completed successfully.");
            return 0;
        } catch (\Exception $e) {
            $backup->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            AuditEvent::create([
                'user_id' => $userId,
                'action' => 'backup.failed',
                'entity_type' => 'backup',
                'entity_id' => $backup->id,
                'ip' => null,
                'payload' => ['error' => $e->getMessage()],
                'created_at' => now(),
            ]);
            $this->error("Backup failed: " . $e->getMessage());
            return 1;
        }
    }

    private function dumpDatabase(string $dir, string $timestamp): ?string
    {
        $config = config('database.connections.mysql');
        $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');
        $filePath = $dir . DIRECTORY_SEPARATOR . $timestamp . '_db.sql';

        $host = $config['host'];
        $port = (int)($config['port'] ?? 3306);
        $user = $config['username'];
        $pass = $config['password'];
        $dbname = $config['database'];

        // Write credentials to a temp options file to avoid exposing password in process listing
        $optFile = $dir . DIRECTORY_SEPARATOR . '.my_' . uniqid() . '.cnf';
        $optContent = "[client]\nhost=" . $host . "\nport=" . $port . "\nuser=" . $user . "\n";
        if (!empty($pass)) {
            $optContent .= "password=" . $pass . "\n";
        }
        file_put_contents($optFile, $optContent);
        chmod($optFile, 0600);

        $cmd = sprintf('%s --defaults-extra-file=%s %s > %s 2>&1',
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
        return match($preset) {
            'storage_app' => storage_path('app'),
            default => base_path(),
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
            $zip = new ZipArchive();
            $zip->open($outPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile($filePath, basename($filePath));
            $zip->close();
            return $outPath;
        } else {
            $outPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.tar.gz';
            $cmd = sprintf('tar -czf %s -C %s %s 2>&1',
                escapeshellarg($outPath),
                escapeshellarg(dirname($filePath)),
                escapeshellarg(basename($filePath))
            );
            exec($cmd, $output, $code);
            if ($code !== 0) throw new \RuntimeException('tar failed: ' . implode("\n", $output));
            return $outPath;
        }
    }

    private function archiveDirectory(string $sourceDir, array $excludes, string $outDir, string $baseName, string $fmt): string
    {
        if ($fmt === 'zip') {
            $outPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.zip';
            $zip = new ZipArchive();
            $zip->open($outPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $this->addDirectoryToZip($zip, $sourceDir, $excludes, $sourceDir);
            $zip->close();
            return $outPath;
        } else {
            $outPath = $outDir . DIRECTORY_SEPARATOR . $baseName . '.tar.gz';
            $excludeArgs = implode(' ', array_map(fn($e) => '--exclude=' . escapeshellarg($e), $excludes));
            $cmd = sprintf('tar -czf %s %s -C %s . 2>&1',
                escapeshellarg($outPath),
                $excludeArgs,
                escapeshellarg($sourceDir)
            );
            exec($cmd, $output, $code);
            if ($code !== 0) throw new \RuntimeException('tar failed: ' . implode("\n", $output));
            return $outPath;
        }
    }

    private function addDirectoryToZip(ZipArchive $zip, string $baseDir, array $excludes, string $currentDir): void
    {
        $items = scandir($currentDir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $fullPath = $currentDir . DIRECTORY_SEPARATOR . $item;
            $relativePath = ltrim(str_replace($baseDir, '', $fullPath), DIRECTORY_SEPARATOR . '/');

            foreach ($excludes as $exclude) {
                $excludeNorm = str_replace('/', DIRECTORY_SEPARATOR, $exclude);
                if ($relativePath === $excludeNorm || strpos($relativePath, $excludeNorm . DIRECTORY_SEPARATOR) === 0) {
                    continue 2;
                }
            }

            if (is_dir($fullPath)) {
                $zip->addEmptyDir($relativePath);
                $this->addDirectoryToZip($zip, $baseDir, $excludes, $fullPath);
            } else {
                $zip->addFile($fullPath, $relativePath);
            }
        }
    }
}
