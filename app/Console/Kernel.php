<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AutoFinishReservation; // Import the command

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AutoFinishReservation::class,
        Commands\SendReservationReminders::class,
        Commands\CheckPencilBookingExpiration::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reservations:auto-finish')->daily();
        $schedule->command('reservations:send-reminders')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}