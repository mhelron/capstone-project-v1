<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReservationReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function envelope()
    {
        return new \Illuminate\Mail\Mailables\Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                env('MAIL_CLIENTS_FROM_ADDRESS', 'no-reply@kylaandkylecatering.com'),
                env('MAIL_CLIENTS_FROM_NAME', 'Kyla and Kyle Catering Services')
            ),
            subject: 'Reminder: Your Upcoming Event Reservation'
        );
    }

    public function content()
    {
        Log::info('Building reminder email with reservation data: ', $this->reservation);
        
        return new \Illuminate\Mail\Mailables\Content(
            view: 'emails.reservation_reminder',
            with: [
                'first_name' => $this->reservation['first_name'],
                'last_name' => $this->reservation['last_name'],
                'phone' => $this->reservation['phone'],
                'email' => $this->reservation['email'],
                'region' => $this->reservation['region'],
                'province' => $this->reservation['province'],
                'city' => $this->reservation['city'],
                'barangay' => $this->reservation['barangay'],
                'street_houseno' => $this->reservation['street_houseno'],
                'package_name' => $this->reservation['package_name'],
                'event_title' => $this->reservation['event_title'],
                'menu_name' => $this->reservation['menu_name'],
                'menu_content' => $this->reservation['menu_content'],
                'guests_number' => $this->reservation['guests_number'],
                'sponsors' => $this->reservation['sponsors'] ?? 'N/A',
                'event_date' => $this->reservation['event_date'],
                'event_time' => $this->reservation['event_time'],
                'venue' => $this->reservation['venue'],
                'theme' => $this->reservation['theme'],
                'other_requests' => $this->reservation['other_requests'] ?? 'N/A',
                'status' => $this->reservation['status'],
                'total_price' => $this->reservation['total_price'] ?? '0.00',
                'payment_status' => $this->reservation['payment_status'],
                'created_at' => $this->reservation['created_at'],
                'reference_number' => $this->reservation['reference_number'],
            ]
        );
    }
}