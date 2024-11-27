<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Kreait\Firebase\Contract\Database;
use Illuminate\Support\Facades\Log;

class AutoFinishReservation extends Command
{
    protected $signature = 'reservations:auto-finish';
    protected $description = 'Automatically finish reservations at 11:59 PM';

    private $database;
    private $reservations = 'reservations';

    public function __construct(Database $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    public function handle()
    {
        $currentDate = Carbon::now('Asia/Manila')->format('Y-m-d'); // Today's date in Manila timezone
        $currentTime = Carbon::now('Asia/Manila')->format('H:i'); // Current time in 24-hour format
        Log::info("Current Time: $currentTime, Current Date: $currentDate");

        if ($currentTime === '23:59') {
            // Fetch all reservations
            $reservations = $this->database->getReference($this->reservations)->getValue();

            if ($reservations) {
                foreach ($reservations as $key => $reservation) {
                    // Check if the reservation is for today and has status "Confirmed"
                    if (
                        isset($reservation['date']) &&
                        $reservation['date'] === $currentDate &&
                        isset($reservation['status']) &&
                        $reservation['status'] === 'Confirmed' // Only target "Confirmed" reservations
                    ) {
                        // Log to debug the reservation
                        Log::info('Auto finishing reservation', ['reservation' => $reservation]);

                        // Mark the reservation as finished
                        $this->database->getReference($this->reservations . '/' . $key)->update(['status' => 'Finished']);
                    }
                }
                $this->info('Confirmed reservations for ' . $currentDate . ' auto-finished successfully.');
            } else {
                $this->info('No reservations to auto-finish.');
            }
        }

        return 0;
    }
}