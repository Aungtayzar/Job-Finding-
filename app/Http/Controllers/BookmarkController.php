<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Job;
use Illuminate\Http\RedirectResponse;

class BookmarkController extends Controller
{
    //@desc get all user bookmarks
    //@route  GET /bookmarks
    public function index():View
    {
        $user = Auth::user();

        $bookmarks = $user->bookmarkedJobs()->orderBy('job_user_bookmarks.created_at','desc')->paginate(9);
        return view('jobs.bookmarked',compact('bookmarks'));
        
    }

    //@desc Create new bookmarked job
    //@route  POST /bookmarks
    public function store(Job $job):RedirectResponse
    {
        $user = Auth::user();

        //Check if the job is already bookmark
        if($user->bookmarkedJobs()->where('job_id',$job->id)->exists()){
            return back()->with('error','Job is already bookmarked');
        }

        //Create new bookmarks
        $user->bookmarkedJobs()->attach($job->id);

        return back()->with('success','Job bookmarked successfully!');
        
    }

    //@desc Delete bookmarked job
    //@route  DELETE /bookmarks
    public function destroy(Job $job):RedirectResponse
    {
        $user = Auth::user();

        //Check if the job is already bookmark
        if(!$user->bookmarkedJobs()->where('job_id',$job->id)->exists()){
            return back()->with('error','Job is not bookmarked');
        }

        //Remove bookmarks
        $user->bookmarkedJobs()->detach($job->id);

        return back()->with('success','Bookmark removed successfully!');
        
    }
} 
