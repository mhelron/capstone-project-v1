<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotationData;

    public function __construct($quotationData)
    {
        $this->quotationData = $quotationData;
    }

    public function build()
    {
        $subject = 'Quotation Request Status Update';
        
        if (isset($this->quotationData['status'])) {
            $status = ucfirst($this->quotationData['status']);
            $subject = "Quotation Request {$status}";
        }

        return $this->subject($subject)
                    ->markdown('emails.status-update')
                    ->with([
                        'quotationData' => $this->quotationData
                    ]);
    }
}