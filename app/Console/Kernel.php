<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $now=Carbon::now()->format('YmdHis');

        // $schedule->command('period:start')->dailyAt('00:01');

        $schedule->command('AlertExpiredPeriodCommand')
            ->timezone('Asia/Jakarta')
            ->dailyAt('08:00');
            // ->everyMinute();
            // ->sendOutputTo("storage/logs/LogAlertExpired_".$now.".txt");

        $schedule->command('ReminderSubmitPeriodCommand')
            ->timezone('Asia/Jakarta')
            ->dailyAt('08:00');
            // ->everyMinute()
            // ->sendOutputTo("storage/logs/LogReminderSubmit_".$now.".txt");
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
