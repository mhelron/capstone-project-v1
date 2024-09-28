<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function showLoginForm()
    {
        if (Session::has('firebase_id_token')) {
            return redirect()->route('admin.dashboard')->with('status', 'You are already logged in.');
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
            $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $this->auth->getUser($uid);

            Session::put('firebase_user', $user);
            Session::put('firebase_id_token', $idTokenString);

            return redirect()->route('admin.dashboard')->with('status', 'Logged in successfully!');
        } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
            return redirect()->back()->with('error', 'The token is invalid: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
        }
    }

    public function logout()
    {   
        Session::flush();
        Session::forget('firebase_user');
        Session::forget('firebase_id_token');
        return redirect()->route('login')->with('status', 'Logged out successfully!');
    }
}