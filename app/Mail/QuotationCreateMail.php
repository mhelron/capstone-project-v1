<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotationData;

    public function __construct($quotationData)
    {
        $this->quotationData = $quotationData;
    }

    public function build()
    {
        return $this->subject('Quotation Request Confirmation')
                    ->markdown('emails.create')
                    ->with([
                        'quotationData' => $this->quotationData
                    ]);
    }
}