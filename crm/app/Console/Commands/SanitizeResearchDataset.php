<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;

class SanitizeResearchDataset extends Command
{
    protected $signature = 'research:sanitize-dataset
                            {--source= : Source Research/ directory (default: storage/app/research/Research)}
                            {--dest=   : Destination directory (default: storage/app/research/Research_win)}
                            {--dry-run : Show what would be done without writing files}';

    protected $description = 'Generate a Windows-safe copy of the Research dataset by sanitizing directory names';

    /**
     * Characters that are invalid in Windows file/directory names.
     * In addition to '/', Windows forbids: < > : " \ | ? *
     * We also strip leading/trailing dots and spaces.
     */
    private const WINDOWS_INVALID = ['/', '\\', '<', '>', ':', '"', '|', '?', '*'];

    public function handle(): int
    {
        $source  = $this->option('source') ?? storage_path('app/research/Research');
        $dest    = $this->option('dest')   ?? storage_path('app/research/Research_win');
        $dryRun  = (bool) $this->option('dry-run');

        if (! is_dir($source)) {
            $this->error("Source directory not found: {$source}");
            $this->line('Download the dataset from usrobotix/yandex-direct and place the Research/ folder there.');
            return Command::FAILURE;
        }

        $this->info("Source : {$source}");
        $this->info("Dest   : {$dest}");
        if ($dryRun) {
            $this->warn('[DRY-RUN] No files will be written.');
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        $copied = 0;
        $skipped = 0;

        foreach ($iterator as $item) {
            $relativePath = $this->relativePath($source, $item->getPathname());
            $safePath     = $this->sanitizePath($relativePath);
            $destPath     = $dest . DIRECTORY_SEPARATOR . $safePath;

            if ($item->isDir()) {
                if (! $dryRun) {
                    if (! is_dir($destPath)) {
                        mkdir($destPath, 0755, true);
                    }
                } else {
                    $this->line("  [dir]  {$safePath}");
                }
                continue;
            }

            // Only copy clinic.yaml and review.md
            $filename = $item->getFilename();
            if (! in_array($filename, ['clinic.yaml', 'review.md'], true)) {
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  [copy] {$safePath}");
                $copied++;
                continue;
            }

            $destDir = dirname($destPath);
            if (! is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            copy($item->getPathname(), $destPath);
            $copied++;
        }

        $this->newLine();
        if ($dryRun) {
            $this->info("Would copy {$copied} file(s). (skipped {$skipped} other files)");
        } else {
            $this->info("Done. Copied {$copied} file(s) to {$dest}. (skipped {$skipped} other files)");
            $this->line("You can now import with:");
            $this->line("  php artisan research:import-partners --path=\"{$dest}\"");
        }

        return Command::SUCCESS;
    }

    /**
     * Return path relative to the source root, using DIRECTORY_SEPARATOR.
     */
    private function relativePath(string $root, string $absolute): string
    {
        $root = rtrim($root, '/\\');
        $rel  = ltrim(substr($absolute, strlen($root)), '/\\');
        return $rel;
    }

    /**
     * Sanitize a relative path so that each segment is safe on Windows.
     * - Replaces '/' with ' - ' within segment names (these come from Linux paths
     *   where the real slash is part of a directory name, e.g. "Нейрохирургия / Ортопедия")
     * - Removes other Windows-invalid characters: < > : " \ | ? *
     * - Strips leading/trailing dots and spaces from each segment.
     */
    private function sanitizePath(string $relativePath): string
    {
        // Split on OS separator (the iterator always returns native paths)
        $segments = explode(DIRECTORY_SEPARATOR, $relativePath);

        $safe = array_map(function (string $segment) {
            // Replace '/' inside segment names with ' - '
            $segment = str_replace('/', ' - ', $segment);

            // Remove remaining Windows-invalid chars (except '/' already replaced)
            $segment = str_replace(['\\', '<', '>', ':', '"', '|', '?', '*'], '', $segment);

            // Strip control characters
            $segment = preg_replace('/[\x00-\x1f\x7f]/', '', $segment);

            // Strip leading/trailing dots and spaces
            $segment = trim($segment, ". \t\n\r\0\x0B");

            if ($segment === '') {
                $this->warn("  [warn] Empty segment after sanitization replaced with '_'");
                return '_';
            }

            return $segment;
        }, $segments);

        return implode(DIRECTORY_SEPARATOR, $safe);
    }
}
