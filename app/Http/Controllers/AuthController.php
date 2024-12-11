<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Database;
use DateTimeImmutable;


class AuthController extends Controller
{
    protected $auth, $database, $users;

    protected const TOKEN_LEEWAY = 300; // 5 minutes leeway

    public function __construct(FirebaseAuth $auth, Database $database)
    {
        $this->auth = $auth;
        $this->database = $database;
        $this->users = 'users';
    }

    public function showLoginForm()
    {
        if (Session::has('firebase_id_token')) {
            try {
                $this->verifyStoredToken();
                return redirect()->route('admin.dashboard')->with('status', 'You are already logged in.');
            } catch (\Exception $e) {
                Session::flush();
            }
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Firebase Authentication
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $idTokenString = $signInResult->idToken();
            $refreshToken = $signInResult->refreshToken();
            
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString, self::TOKEN_LEEWAY);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $this->auth->getUser($uid);

            // Check if email is verified
            if (!$user->emailVerified) {
                Session::put('firebase_user', $user); // Store user temporarily for verification
                return redirect()->route('verification.notice');
            }

            // Fetch user role from Realtime Database
            $userRecord = $this->database->getReference('users')
                ->orderByChild('email')
                ->equalTo($request->email)
                ->getSnapshot();

            if (!$userRecord->hasChildren()) {
                throw new \Exception('User role not found');
            }

            $userData = current($userRecord->getValue());
            $userRole = $userData['user_role'] ?? null;
            $fname = $userData['fname'] ?? null;

            if (!$userRole) {
                throw new \Exception('Invalid user role');
            }

            $expirationTime = $verifiedIdToken->claims()->get('exp');
            if ($expirationTime instanceof DateTimeImmutable) {
                $expirationTime = $expirationTime->getTimestamp();
            }

            // Store all necessary session data
            Session::put([
                'firebase_user' => $user,
                'firebase_id_token' => $idTokenString,
                'firebase_refresh_token' => $refreshToken,
                'token_expiration' => $expirationTime,
                'user_role' => $userRole,
                'fname' => $fname
            ]);

            Log::info('Activity Log', [
                'user' => $user->email,
                'role' => $userRole,
                'action' => 'User logged in.'
            ]);

            return $this->roleBasedRedirect($userRole);

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Authentication failed. Please try again.']);
        }
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $this->auth->sendEmailVerificationLink($request->email);
            return redirect()->back()->with('status', 'Verification email has been resent.');
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send verification email. Please try again.');
        }
    }

    protected function roleBasedRedirect($userRole)
    {
        switch ($userRole) {
            case 'Super Admin':
                return redirect('/admin/dashboard')->with('status', 'Logged In as ' . session('fname') . ' (Super Admin)');
            case 'Admin':
                return redirect('/admin/dashboard')->with('status', 'Logged In as ' . session('fname') . ' (Admin)');
            case 'Manager':
                return redirect('/admin/dashboard')->with('status', 'Logged In as ' . session('fname') . ' (Manager)');
            case 'Staff':
                return redirect('/admin/dashboard')->with('status', 'Logged In as ' . session('fname') . ' (Staff)');
            default:
                return redirect('login')->with('status', 'Invalid User Role');
        }
    }

    public function logout()
    {
        $user = Session::get('firebase_user');

        Log::info('Activity Log', [
            'user' => $user->email,
            'action' => 'User logged out.'
        ]);

        Session::flush();
        return redirect()->route('login')->with('status', 'Logged out successfully!');
    }

    protected function verifyStoredToken()
    {
        $token = Session::get('firebase_id_token');
        $tokenExpiration = Session::get('token_expiration');
        $currentTime = Carbon::now()->timestamp;

        // If token has expired, refresh it
        if (!$token || $currentTime >= $tokenExpiration) {
            return $this->refreshIdToken();  // Refresh the token
        }

        $user = Session::get('firebase_user');

        Log::info('Activity Log', [
            'user' => $user->email,
            'action' => 'Session Expired'
        ]);

        return $this->auth->verifyIdToken($token, self::TOKEN_LEEWAY);
    }

    protected function refreshIdToken()
    {
        $refreshToken = Session::get('firebase_refresh_token');
        if (!$refreshToken) {
            throw new \Exception('No refresh token found');
        }

        try {
            $signInResult = $this->auth->signInWithRefreshToken($refreshToken);
            $idTokenString = $signInResult->idToken();

            // Verify the new ID token
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString, self::TOKEN_LEEWAY);
            $expirationTime = $verifiedIdToken->claims()->get('exp');
            if ($expirationTime instanceof DateTimeImmutable) {
                $expirationTime = $expirationTime->getTimestamp();
            }

            // Update session with the new ID token and expiration time
            Session::put([
                'firebase_id_token' => $idTokenString,
                'token_expiration' => $expirationTime
            ]);

            $user = Session::get('firebase_user');

            Log::info('Activity Log', [
                'user' => $user->email,
                'action' => 'Session Expired'
            ]);

            return $verifiedIdToken;
        } catch (\Exception $e) {
            throw new \Exception('Failed to refresh token: ' . $e->getMessage());
        }
    }
}