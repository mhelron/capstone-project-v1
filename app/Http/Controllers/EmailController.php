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
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $email = 'Mhelron6@gmail.com';

        // Pass the validated data directly to the ContactUsMail class
        Mail::to($email)->send(new ContactUsMail($validatedData));

        return back()->with('status', 'Message sent successfully!');
    }
}
