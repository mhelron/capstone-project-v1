<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class PencilConfirmationMail extends Mailable
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

        return $this->mailer('clients')  // Specify the 'clients' mailer here
                    ->view('emails.pencil_confirmation')
                    ->with([
                        'first_name' => $this->reservation['first_name'],
                        'last_name' => $this->reservation['last_name'],
                        'package_name' => $this->reservation['package_name'],
                        'menu_name' => $this->reservation['menu_name'],
                        'menu_content' => $this->reservation['menu_content'], // Access from the reservation data
                        'event_date' => $this->reservation['event_date'],
                        'guests_number' => $this->reservation['guests_number'],
                        'sponsors' => $this->reservation['sponsors'] ?? 'N/A',
                        'venue' => $this->reservation['venue'],
                        'event_time' => $this->reservation['event_time'],
                        'theme' => $this->reservation['theme'],
                        'other_requests' => $this->reservation['other_requests'] ?? 'N/A',
                        'status' => $this->reservation['status'],
                        'total_price' => $this->reservation['total_price'] ?? '0.00',
                    ])
                    ->subject('Your Pencil Reservation Confirmation');
    }

}
