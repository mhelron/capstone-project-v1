<?php

namespace App\Http\Middleware;

use Closure;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use DateTimeImmutable;

class AuthMiddleware
{
    protected $firebaseAuth;
    protected const TOKEN_LEEWAY = 300;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function handle($request, Closure $next, ...$roles)
    {
        $idToken = Session::get('firebase_id_token');
        
        if (!$idToken) {
            return $this->clearSessionAndRedirect('Access Denied. Please log in.');
        }

        if (!Session::has('user_role')) {
            return $this->clearSessionAndRedirect('User role not found. Please log in again.');
        }

        $userRole = Session::get('user_role');

        // If roles are specified and user's role is not in the allowed roles
        if (!empty($roles) && !in_array($userRole, $roles)) {
            return redirect()->route('unauthorized')->with('error', 'You do not have permission to access this page.');
        }

        try {
            $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idToken, self::TOKEN_LEEWAY);

            $expirationTime = $verifiedIdToken->claims()->get('exp');
            if ($expirationTime instanceof DateTimeImmutable) {
                $expirationTime = $expirationTime->getTimestamp();
            }
            
            $expiration = Carbon::createFromTimestamp($expirationTime);

            if ($expiration->subMinutes(5)->isPast()) {
                $user = Session::get('firebase_user');
                Log::info('Activity Log', [
                    'user' => $user->email,
                    'role' => $userRole,
                    'action' => 'Session Expired'
                ]);
                return $this->clearSessionAndRedirect('Session expired. Please log in again.');
            }

            $uid = $verifiedIdToken->claims()->get('sub');
            
            try {
                $firebaseUser = $this->firebaseAuth->getUser($uid);
                Session::put('firebase_uid', $firebaseUser->uid);
            } catch (UserNotFound $e) {
                return $this->clearSessionAndRedirect('User not found. Please log in again.');
            }

        } catch (FailedToVerifyToken $e) {
            return $this->clearSessionAndRedirect('Session invalid. Please log in again.');
        } catch (\Exception $e) {
            Log::error('Auth middleware error: ' . $e->getMessage());
            return $this->clearSessionAndRedirect('An error occurred. Please try again.');
        }

        return $next($request);
    }

    protected function clearSessionAndRedirect($message)
    {
        Session::flush();
        return redirect()->route('login')->with('error', $message);
    }
}