<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{   
    // @desc show user job listing 
    // @route  Get /dashboard
    public function index():View{
        //Get login User 
        $user = Auth::user();

        //get user job listing 
        $jobs = Job::where('user_id',$user->id)->with('applicants')->get();

        //get a job that user is applied to
        $appliedJobs = auth()->user()->appliedJobs()->get();
        // dd($appliedJobs);

        return view('dashboard.index',compact('user','jobs','appliedJobs'));

    }
}
