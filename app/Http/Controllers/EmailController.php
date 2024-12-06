<?php

namespace App\Http\Controllers;
use App\Mail\ContactUsMail;
use App\Mail\SampleEmail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function sendEmail()
    {
        $email = 'Mhelron6@gmail.com';
        $data = 'Mhelron';

        Mail::to($email)->send(new SampleEmail($data));

        return 'Email sent successfully!';
    }

    public function sendContactus(Request $request)
    {
        // Validate the contact form data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|regex:/^09\d{9}$/',
            'email' => 'required|email',
            'message' => 'required|string',
        ], [
            'name.required' => 'Your name is required.',
            'phone.required' => 'Please provide your phone number.',
            'phone.regex' => 'The phone number must start with "09" and contain exactly 11 digits.',
            'email.required' => 'An email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'message.required' => 'You must enter a message.',
        ]);

        // Send the contact email using the 'admin' mailer
        Mail::mailer('admin')  // Specify 'admin' mailer here
            ->to('contactus@kylaandkylecatering.com')  // The recipient email
            ->send(new ContactUsMail($validatedData));

        return back()->with('success', 'Your message has been sent!');
    }
}
