<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $settings = \App\Models\BackupSetting::instance();
            if (!$settings->enabled) return;

            $backup = \App\Models\Backup::create([
                'type'             => $settings->backup_type,
                'file_preset'      => in_array($settings->backup_type, ['files', 'full']) ? $settings->file_preset : null,
                'formats'          => $settings->formats ?? ['zip', 'tar_gz'],
                'local_paths'      => null,
                'remote_paths'     => null,
                'size_bytes'       => 0,
                'status'           => 'queued',
                'initiated_by'     => 'cron',
                'user_id'          => null,
                'progress_percent' => 0,
                'current_step'     => 'queued',
            ]);

            \App\Jobs\RunBackupJob::dispatch($backup->id, (bool)$settings->upload_yandex);
            \Illuminate\Support\Facades\Artisan::call('backup:prune', ['--keep' => 20]);
        })->dailyAt($this->getScheduleTime());

        $schedule->command('audit:prune --days=360')->daily();
    }

    private function getScheduleTime(): string
    {
        try {
            return \App\Models\BackupSetting::instance()->schedule_time ?? '03:00';
        } catch (\Exception) {
            return '03:00';
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
