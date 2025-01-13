<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // @desc show profile info 
    // @route PUT /profile 
    public function update(Request $request):RedirectResponse{
        //Get logged in user
        $user = Auth::user();

        //Validate data
        $validateddata = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email'
        ]);

        //Update user info
        $user->update($validateddata);

        return redirect()->route('dashboard')->with('success','Updated Profile Info!');


    }
}
