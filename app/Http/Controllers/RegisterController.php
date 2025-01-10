<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    // @desc Show register form 
    // @route GET /register 
    public function register():View
    {
        return view('auth.register');
    }

    // @desc Show register form 
    // @route POST /register 
    public function store(Request $request):RedirectResponse
    {
        $validateData = $request->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|string|email|max:100|unique:users',
            'password'=>'required|string|min:8|confirmed'
        ]);

        //Hash Password
        $validateData['password'] = Hash::make($validateData['password']);

        //Create User 
        $user = User::create($validateData);
        
        return redirect()->route('login')->with('success','You are registered and ready to Login!');
    }
}
