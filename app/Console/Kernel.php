<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\AutoFinishReservation; // Import the command

class Kernel extends ConsoleKernel
{
    protected $commands = [
        AutoFinishReservation::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reservations:auto-finish')->dailyAt('23:59');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}