<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use DateTimeImmutable;


class AuthController extends Controller
{
    protected $auth;
    protected const TOKEN_LEEWAY = 300; // 5 minutes leeway

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
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
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);
            $idTokenString = $signInResult->idToken();
            $refreshToken = $signInResult->refreshToken();  // Get the refresh token

            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString, self::TOKEN_LEEWAY);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $this->auth->getUser($uid);

            // Convert DateTimeImmutable to timestamp for Carbon
            $expirationTime = $verifiedIdToken->claims()->get('exp');
            if ($expirationTime instanceof DateTimeImmutable) {
                $expirationTime = $expirationTime->getTimestamp();
            }

            // Store token, refresh token, and expiration
            Session::put([
                'firebase_user' => $user,
                'firebase_id_token' => $idTokenString,
                'firebase_refresh_token' => $refreshToken,  // Store refresh token
                'token_expiration' => $expirationTime
            ]);

            Log::info("User '{$user->email}' successfully logged in.");


            return redirect()->route('admin.dashboard')->with('status', 'Logged in successfully!');
        } catch (\Kreait\Firebase\Auth\SignIn\FailedToSignIn $e) {
            Log::error('Someone tries to login.');
            return redirect()->back()->with(['error' => 'Invalid Credentials! Please Check Your Email or Password']);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage() . ' | Exception class: ' . get_class($e));
            return redirect()->back()->with(['error' => 'Authentication failed. Please try again.']);
        }
    }

    public function logout()
    {
        $user = Session::get('firebase_user');

        Log::info("User '{$user->email}' successfully logged out.");


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

            return $verifiedIdToken;
        } catch (\Exception $e) {
            throw new \Exception('Failed to refresh token: ' . $e->getMessage());
        }
    }
}