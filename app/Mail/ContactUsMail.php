<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $email;
    public $userMessage;  // Changed from message to userMessage

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->phone = $data['phone'];
        $this->email = $data['email'];
        $this->userMessage = $data['message'];  // Store the message content in userMessage
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->mailer('admin')  // Use the 'admin' mailer
                    ->view('emails.contactus')
                    ->with([
                        'name' => $this->name,
                        'phone' => $this->phone,
                        'email' => $this->email,
                        'messageContent' => $this->userMessage,  // Pass as messageContent to the view
                    ])
                    ->subject('New Contact Form Submission');
    }
}