<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FoodTasteCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The food tasting reservation data.
     *
     * @var array
     */
    public $foodtaste;

    /**
     * Create a new message instance.
     *
     * @param array $foodtaste
     * @return void
     */
    public function __construct($foodtaste)
    {
        $this->foodtaste = $foodtaste;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('Building food tasting email with data: ', $this->foodtaste);

        return $this->mailer('clients')
                ->view('emails.foodtastecreatemail')
                ->with([
                    'firstname' => $this->foodtaste['firstname'],
                    'lastname' => $this->foodtaste['lastname'],
                    'email' => $this->foodtaste['email'],
                    'phone' => $this->foodtaste['phone'],
                    'delivery_option' => $this->foodtaste['delivery_option'],
                    'preferred_time' => $this->foodtaste['preferred_time'],
                    'preferred_date' => $this->foodtaste['preferred_date'],
                    'status' => $this->foodtaste['status'],
                    'reference_number' => $this->foodtaste['reference_number'],
                    'created_at' => $this->foodtaste['created_at'],
                    // Conditional address fields
                    'region' => $this->foodtaste['region'] ?? null,
                    'province' => $this->foodtaste['province'] ?? null,
                    'city' => $this->foodtaste['city'] ?? null,
                    'barangay' => $this->foodtaste['barangay'] ?? null,
                    'street_houseno' => $this->foodtaste['street_houseno'] ?? null,
                ])
                ->subject('Food Tasting Request Confirmation');
    }
}