<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {   // Update Nama Paket Pricing jika sudah masa waktu
        $schedule->command('pricing:update-package')->dailyAt('00:10');

        // Kirim pengingat tiap hari jam 08:00
        $schedule->command('pricing:send-reminders')->dailyAt('08:00');
    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
