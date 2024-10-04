<?php

namespace App\Http\Middleware;

use Closure;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AuthMiddleware
{
    protected $firebaseAuth;

    public function __construct(FirebaseAuth $firebaseAuth)
    {
        $this->firebaseAuth = $firebaseAuth;
    }

    public function handle($request, Closure $next)
{
    $idToken = Session::get('firebase_id_token');

    if (!$idToken) {
        return $this->clearSessionAndRedirect('Access Denied. Please log in.');
    }

    try {
        $verifiedIdToken = $this->firebaseAuth->verifyIdToken($idToken);

        $expirationDateTime = $verifiedIdToken->claims()->get('exp');
        $expirationDate = Carbon::instance($expirationDateTime);

        if ($expirationDate->isPast()) {
            return $this->clearSessionAndRedirect('Session expired. Please log in again.');
        }

        $uid = $verifiedIdToken->claims()->get('sub');
        
        try {
            $firebaseUser = $this->firebaseAuth->getUser($uid);
            Session::put('firebase_uid', $firebaseUser->uid);
        } catch (UserNotFound $e) {
            return $this->clearSessionAndRedirect('User data no longer exists. Please log in again.');
        }

    } catch (FailedToVerifyToken $e) {
        return $this->clearSessionAndRedirect('Session expired. Please log in again.');
    } catch (\Exception $e) {
        return $this->clearSessionAndRedirect('An error occurred: ' . $e->getMessage());
    }

    return $next($request);
}


    protected function clearSessionAndRedirect($message)
    {
        $this->clearSession();
        return redirect()->route('login')->with('error', $message);
    }

    protected function clearSession()
    {
        Session::forget('firebase_user');
        Session::forget('firebase_id_token');
    }
}
