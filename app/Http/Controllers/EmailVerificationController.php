<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Show the email verification notice.
     */
    public function notice()
    {
        // If user is already verified, redirect to dashboard
        if (Session::has('firebase_user')) {
            $user = $this->auth->getUser(Session::get('firebase_user')->uid);
            if ($user->emailVerified) {
                return redirect()->route('admin.dashboard');
            }
        }
        
        return view('admin.auth.verifyemail');
    }

    /**
     * Send/Resend verification email
     */
    public function send(Request $request)
    {
        try {
            if (!Session::has('firebase_user')) {
                return redirect()->route('login')
                    ->with('error', 'Please login first.');
            }

            $user = Session::get('firebase_user');
            
            // Send verification email
            $this->auth->sendEmailVerificationLink($user->email);

            return back()->with('status', 'Verification link has been sent to your email address.');
            
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            return back()->with('error', 'Could not send verification email. Please try again later.');
        }
    }

    /**
     * Handle the email verification callback
     */
    public function verify(Request $request)
    {
        try {
            // Get the verification code from the URL
            $oobCode = $request->query('oobCode');
            
            if (!$oobCode) {
                return redirect()->route('verification.notice')
                    ->with('error', 'Invalid verification link.');
            }

            // Verify the email
            $this->auth->verifyEmailVerificationCode($oobCode);

            // Update session if user is logged in
            if (Session::has('firebase_user')) {
                $user = $this->auth->getUser(Session::get('firebase_user')->uid);
                Session::put('firebase_user', $user);
            }

            return redirect()->route('login')
                ->with('status', 'Email verified successfully. You can now login.');

        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            return redirect()->route('verification.notice')
                ->with('error', 'Invalid or expired verification link. Please request a new one.');
        }
    }
}