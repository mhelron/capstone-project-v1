<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function showResetForm()
    {
        return view('admin.auth.passwords.reset'); // Create this blade
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $this->auth->sendPasswordResetLink($request->email);

            return redirect()->back()->with('status', 'Password reset email sent! Check your inbox.');
        } catch (\Exception $e) {
            Log::error('Error sending password reset email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send password reset email.');
        }
    }
}
