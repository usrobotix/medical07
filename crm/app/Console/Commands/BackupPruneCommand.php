<?php
namespace App\Console\Commands;

use App\Models\Backup;
use App\Services\YandexDiskService;
use Illuminate\Console\Command;

class BackupPruneCommand extends Command
{
    protected $signature = 'backup:prune {--keep=20 : Number of backups to keep}';
    protected $description = 'Prune old backups keeping only the last N';

    public function handle(YandexDiskService $yandex): int
    {
        $keep = (int)$this->option('keep');
        $total = Backup::where('status', 'done')->count();
        if ($total <= $keep) {
            $this->info("Nothing to prune ({$total} backups, keeping {$keep}).");
            return 0;
        }
        $toDelete = Backup::where('status', 'done')
            ->orderBy('created_at', 'asc')
            ->limit($total - $keep)
            ->get();

        foreach ($toDelete as $backup) {
            $this->deleteBackupFiles($backup, $yandex);
            $backup->delete();
        }
        $this->info("Pruned " . count($toDelete) . " old backups.");
        return 0;
    }

    public function deleteBackupFiles(Backup $backup, YandexDiskService $yandex): void
    {
        if ($backup->local_paths) {
            $checkedDirs = [];
            foreach ($backup->local_paths as $path) {
                if (is_file($path)) @unlink($path);
                $dir = dirname($path);
                $checkedDirs[$dir] = true;
            }
            foreach (array_keys($checkedDirs) as $dir) {
                if (is_dir($dir) && count(scandir($dir)) === 2) {
                    @rmdir($dir);
                }
            }
        }
        if ($backup->remote_paths && $yandex->isConfigured()) {
            foreach ($backup->remote_paths as $remotePath) {
                $yandex->delete($remotePath);
            }
        }
    }
}
