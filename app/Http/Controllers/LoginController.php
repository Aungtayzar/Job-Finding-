<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // @desc Show login form 
    // @route GET /login 
    public function login():View
    {
        return view('auth.login');
    }

    // @desc Show login form 
    // @route POST /authenticate 
    public function authenticate(Request $request):RedirectResponse
    {
        $credentials = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string'
        ]);

        //Attempt to authenticate user 
        if(Auth::attempt($credentials)){

            //Regenerate the session to prevent fixation attacks

            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success','You are now logged in!');

        }

        //if auth fail, redirect back with error 
        return back()->withErrors([
            'email'=>'The provided credentials do not match our records'
        ])->onlyInput('email');

       
    }

    // @desc Show login form 
    // @route POST /logout 
    public function logout(Request $request):RedirectResponse
    {
        //logout
        Auth::logout();

        //delete seesion key
        $request->session()->invalidate();

        //Regenerate the session to prevent fixation attacks
        $request->session()->regenerate();

        return redirect('/')->with('success','You are logged out!');
    }
}
