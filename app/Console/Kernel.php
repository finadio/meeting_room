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
        // $schedule->command('inspire')->hourly();
        $schedule->command('booking:check-status')->everyFiveMinutes();

        // Tambahkan perintah baru untuk mengirim notifikasi booking_end
        $schedule->command('booking:send-end-notification')->everyFiveMinutes();
        
        // Jadwalkan pengiriman agenda harian otomatis jam 07:00 pagi
        $schedule->command('agenda:send-harian')->dailyAt('07:00');
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
