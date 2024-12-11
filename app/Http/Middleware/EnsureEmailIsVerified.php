<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Session;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Session::get('firebase_user');
        
        // Skip verification check for verification routes
        if ($request->is('email/*')) {
            return $next($request);
        }
        
        if (!$user || !$user->emailVerified) {
            return redirect()->route('verification.notice')
                ->with('error', 'You must verify your email address.');
        }

        return $next($request);
    }
}