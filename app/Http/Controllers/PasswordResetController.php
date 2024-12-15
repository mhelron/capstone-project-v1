<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    // Show the password reset form (already in place for email)
    public function showResetForm(Request $request)
    {
        return view('admin.auth.passwords.new', ['oobCode' => $request->get('oobCode')]);
    }

    // Handle sending the reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $this->auth->sendPasswordResetLink($request->email);
            Log::info('Activity Log', ['user' => $request->email, 'action' => 'Requested a password reset.']);
            return redirect()->back()->with('status', 'Password reset email sent! Check your inbox.');
        } catch (\Exception $e) {
            Log::error('Error sending password reset email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send password reset email.');
        }
    }

    public function showNewPasswordForm()
    {
        return view('admin.auth.passwords.new');
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
            // Use Firebase to reset the password using the oobCode
            $this->auth->confirmPasswordReset($request->oobCode, $request->new_password);

            return redirect()->route('login')->with('status', 'Password reset successful! You can now log in.');
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reset password.');
        }
    }
}
