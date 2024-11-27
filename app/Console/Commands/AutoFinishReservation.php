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
    private $reservations = 'reservations'; // This is your class property, representing the Firebase node path

    public function __construct(Database $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    public function handle()
    {
        $currentDate = Carbon::now('Asia/Manila')->format('Y-m-d'); // Today's date in Manila timezone
        $currentTime = Carbon::now('Asia/Manila')->format('H:i'); // Current time in 24-hour format

        // Logging current date and time for debugging
        Log::info("Current Time: $currentTime, Current Date: $currentDate");

        // Temporarily remove the time check for debugging, or change the condition
        // if ($currentTime === '23:59') {
        // Use a temporary condition to test
        if (true) { // Always run the code block for testing
            // Fetch all reservations from Firebase
            $reservations = $this->database->getReference($this->reservations)->getValue();

            if ($reservations) {
                // Log all fetched reservations for debugging
                Log::info('Fetched Reservations:', ['reservations' => $reservations]);

                foreach ($reservations as $key => $reservation) {
                    // Check if the reservation is for today and has status "Confirmed"
                    if (
                        isset($reservation['event_date']) &&
                        $reservation['event_date'] === $currentDate &&
                        isset($reservation['status']) &&
                        $reservation['status'] === 'Confirmed' // Only target "Confirmed" reservations
                    ) {
                        // Log the specific reservation that is being auto-finished
                        Log::info('Auto finishing reservation', ['reservation_id' => $key, 'reservation' => $reservation]);

                        // Mark the reservation as finished in Firebase
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