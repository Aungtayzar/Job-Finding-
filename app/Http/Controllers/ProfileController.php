<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'email'=>'required|string|email',
            'avatar'=>'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        //Get user and email
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        //Handle avatar upload

        if($request->hasFile('avatar')){

            //Delete old avatar if exit
            Storage::delete('public/' . $user->avatar);

            //Store new avatar 
            $avatarPath = $request->file('avatar')->store('avatars','public');
            $user->avatar = $avatarPath;

        }

        //Update user info
        $user->save();

        return redirect()->route('dashboard')->with('success','Updated Profile Info!');


    }
}
