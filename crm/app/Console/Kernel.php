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

            \Illuminate\Support\Facades\Artisan::call('backup:run', [
                '--type'         => $settings->backup_type,
                '--preset'       => $settings->file_preset,
                '--formats'      => implode(',', $settings->formats ?? ['zip', 'tar_gz']),
                '--yandex'       => $settings->upload_yandex ? 1 : 0,
                '--initiated-by' => 'cron',
            ]);
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
