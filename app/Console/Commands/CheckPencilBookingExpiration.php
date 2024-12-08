<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Contract\Database;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckPencilBookingExpiration extends Command
{
    protected $signature = 'bookings:check-pencil-expiration';
    protected $description = 'Check and update expired pencil bookings';

    private $database;

    public function __construct(Database $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    public function handle()
    {
        $this->info('Checking for expired pencil bookings...');
        
        try {
            $now = Carbon::now()->toDateTimeString();
            
            // Get all pencil bookings
            $pencilBookings = $this->database->getReference('reservations')
                ->orderByChild('status')
                ->equalTo('Pencil')
                ->getValue();

            if (!$pencilBookings) {
                $this->info('No pencil bookings found.');
                return;
            }

            $expiredCount = 0;

            foreach ($pencilBookings as $key => $booking) {
                if (isset($booking['pencil_expires_at'])) {
                    $expirationDate = Carbon::parse($booking['pencil_expires_at']);
                    
                    if ($expirationDate->isPast()) {
                        // Update expired booking status
                        $this->database->getReference('reservations')->getChild($key)->update([
                            'status' => 'Expired',
                            'expired_at' => $now
                        ]);

                        $expiredCount++;

                        // Log the expiration
                        Log::info('Pencil booking expired', [
                            'reservation_id' => $key,
                            'expired_at' => $now
                        ]);

                        $this->line("Expired booking updated: {$booking['reference_number']}");
                    }
                }
            }

            $this->info("Check completed. {$expiredCount} expired bookings found and updated.");

        } catch (\Exception $e) {
            Log::error('Error checking expired pencil bookings', [
                'error' => $e->getMessage()
            ]);
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}