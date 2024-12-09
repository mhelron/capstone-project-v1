<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpiredReservation extends Mailable
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
                ->view('emails.expiredreservation')
                ->with([
                    'first_name' => $this->reservation['first_name'],
                    'last_name' => $this->reservation['last_name'],
                ])
                ->subject('Cancelled Reservation');
    }
}