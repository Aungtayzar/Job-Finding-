<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // @desc   Show all jobs
    // @route  GET /jobs
    public function index()
    {
        
        $jobs = Job::all();
        return view('jobs.index')->with('jobs',$jobs);
    }

    // @desc   Show create job form
    // @route  GET /jobs/create
    public function create()
    {
        return view('jobs.create');
    }

    // @desc   Store a new job
    // @route  POST /jobs
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'salary'=>'required|integer',
            'tags'=>'nullable|string',
            'job_type'=>'required|string',
            'remote'=>'required|boolean',
            'requirements'=>'nullable|string',
            'benefits'=>'nullable|string',
            'address'=>'nullable|string',
            'city'=>'required|string',
            'state'=>'required|string',
            'contact_email'=>'required|string',
            'contact_phone'=>'nullable|string',
            'company_name'=>'required|string',
            'company_description'=>'nullable|string',
            'company_logo'=>'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'company_website'=>'nullable|url'
        ]);

        //Hard code user_id
        $validateData['user_id'] = 1;

        Job::create($validateData);

        return redirect()->route('jobs.index')->with('success','Job listing created successfully!');
    }

    // @desc   Show a single job
    // @route  GET /jobs/{id}
    public function show(Job $job)
    {
        return view('jobs.show')->with('job',$job);
    }

    // @desc   Show the form for editing a job
    // @route  GET /jobs/{id}/edit
    public function edit(string $id)
    {
         return "Edit job $id";
    }

    // @desc   Update a job
    // @route  PUT /jobs/{id}
    public function update(Request $request, string $id)
    {
         return "Update job $id";
    }

    // @desc  Delete a job
    // @route DELETE /jobs/{id}
    public function destroy(string $id)
    {
          return "Delete job $id";
    }
}