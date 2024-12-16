<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    // Show the initial reset password form
    public function showResetForm()
    {
        return view('admin.auth.passwords.reset');
    }

    // Handle sending the reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            try {
                $user = $this->auth->getUserByEmail($request->email);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'No account found with this email address.');
            }

            $resetUrl = 'https://www.kylaandkylecatering.com/new-password';
            
            $actionCodeSettings = [
                'continueUrl' => $resetUrl,
                'handleCodeInApp' => true
            ];
            
            $link = $this->auth->getPasswordResetLink($request->email, $actionCodeSettings);
            
            // Log the link to verify it's being generated
            Log::info('Password Reset Link Generated', [
                'email' => $request->email,
                'link' => $link
            ]);

            return redirect()->back()->with('status', 'Password reset email sent! Check your inbox.');
        } catch (\Exception $e) {
            Log::error('Firebase Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send reset email: ' . $e->getMessage());
        }
    }

    // Show the new password form
    public function showNewPasswordForm(Request $request)
    {
        // Validate that we have an oobCode
        if (!$request->has('oobCode')) {
            return redirect()->route('password.reset.form')
                           ->with('error', 'Invalid password reset link.');
        }

        return view('admin.auth.passwords.new', [
            'oobCode' => $request->oobCode
        ]);
    }

    // Handle the password reset confirmation
    public function confirmReset(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
            'oobCode' => 'required'
        ]);

        try {
            $this->auth->confirmPasswordReset($request->oobCode, $request->new_password);
            return redirect()->route('login')->with('status', 'Password reset successful! You can now log in.');
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reset password.');
        }
    }
}
