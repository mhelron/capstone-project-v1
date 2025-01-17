<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FoodTasteStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $foodtaste;

    public function __construct($foodtaste)
    {
        $this->foodtaste = $foodtaste;
    }

    public function build()
    {
        Log::info('Building food tasting email with data: ', $this->foodtaste);
        
        $subject = 'Food Tasting Request ' . ucfirst($this->foodtaste['status']);

        return $this->mailer('clients')
            ->view('emails.foodtaste_status_update')
            ->with([
                'firstname' => $this->foodtaste['firstname'],
                'lastname' => $this->foodtaste['lastname'],
                'reference_number' => $this->foodtaste['reference_number'],
                'status' => $this->foodtaste['status'],
                'set_time' => $this->foodtaste['set_time'] ?? null,
                'set_date' => $this->foodtaste['set_date'] ?? null,
                'delivery_option' => $this->foodtaste['delivery_option']
            ])
            ->subject($subject);
    }
}