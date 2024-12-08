<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelByUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $reservation;

    /**
     * Create a new message instance.
     *
     * @param array $reservation
     * @return void
     */
    public function __construct($reservation)
    {
        $this->reservation = $reservation;  // Store the reservation data
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('Building email with reservation data: ', $this->reservation);
        Log::info('Reservation data for email:', $this->reservation);

        return $this->mailer('clients')
                ->view('emails.cancelbyuser_confirmation')
                ->with([
                    'first_name' => $this->reservation['first_name'],
                    'last_name' => $this->reservation['last_name'],
                    'reference_number' => $this->reservation['reference_number'],
                    'status' => $this->reservation['status'],
                    'cancellation_reason' => $this->reservation['cancellation_reason'],
                    'cancelled_at' => $this->reservation['cancelled_at'],

                ])
                ->subject('Cancelled Reservation');
    }
}