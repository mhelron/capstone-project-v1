<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Kreait\Firebase\Contract\Database;
use App\Mail\ReservationReminderMail;
use Illuminate\Support\Facades\Log;

class SendReservationReminders extends Command
{
    protected $signature = 'reservations:send-reminders';
    protected $description = 'Send email reminders for reservations happening in 1 week';

    protected $database;

    public function __construct(Database $database)
    {
        parent::__construct();
        $this->database = $database;
    }

    public function handle()
    {
        $this->info('Starting to check for upcoming reservations...');

        // Get reference to your reservations in Firebase
        $reservationsRef = $this->database->getReference('reservations');
        $snapshot = $reservationsRef->getValue();

        if (!$snapshot) {
            $this->info('No reservations found.');
            return;
        }

        $oneWeekFromNow = Carbon::now()->addWeek()->startOfDay();
        $remindersCount = 0;

        foreach ($snapshot as $key => $reservation) {
            try {
                // Convert event_date to Carbon instance for comparison
                $eventDate = Carbon::createFromFormat('Y-m-d', $reservation['event_date'])->startOfDay();

                // Check if the reservation is exactly one week away
                if ($eventDate->eq($oneWeekFromNow)) {
                    // Send email using specific mailer
                    Mail::mailer('clients')
                        ->to($reservation['email'])
                        ->send(new ReservationReminderMail($reservation));
                    
                    $remindersCount++;
                    
                    $this->info("Sent reminder for reservation {$reservation['reference_number']} to {$reservation['email']}");
                    
                    // Log successful sending
                    Log::info('Reminder email sent', [
                        'reference_number' => $reservation['reference_number'],
                        'email' => $reservation['email'],
                        'event_date' => $reservation['event_date']
                    ]);
                }
            } catch (\Exception $e) {
                $this->error("Failed to process reservation {$key}: {$e->getMessage()}");
                Log::error('Failed to send reminder email', [
                    'reference_number' => $reservation['reference_number'] ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Completed sending reminders. Sent {$remindersCount} reminder(s).");
    }
}