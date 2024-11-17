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
            
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString, self::TOKEN_LEEWAY);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $this->auth->getUser($uid);

            // Convert DateTimeImmutable to timestamp for Carbon
            $expirationTime = $verifiedIdToken->claims()->get('exp');
            if ($expirationTime instanceof DateTimeImmutable) {
                $expirationTime = $expirationTime->getTimestamp();
            }
            
            // Store token and expiration
            Session::put([
                'firebase_user' => $user,
                'firebase_id_token' => $idTokenString,
                'token_expiration' => $expirationTime
            ]);

            return redirect()->route('admin.dashboard')->with('status', 'Logged in successfully!');

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Authentication failed. Please try again.');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('status', 'Logged out successfully!');
    }

    protected function verifyStoredToken()
    {
        $token = Session::get('firebase_id_token');
        if (!$token) {
            throw new \Exception('No token found');
        }
        return $this->auth->verifyIdToken($token, self::TOKEN_LEEWAY);
    }
}