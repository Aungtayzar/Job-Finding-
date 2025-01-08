<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

        //Check for image
        if($request->hasFile('company_logo')){

            //Store file and get path
            $path = $request->file('company_logo')->store('logos','public');

            // send image path to database 
            $validateData['company_logo'] = $path;
        }

        //Submit to database
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
    public function edit(Job $job)
    {
        return view('jobs.edit')->with('job',$job);
         
    }

    // @desc   Update a job
    // @route  PUT /jobs/{id}
    public function update(Request $request, Job $job)
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


        //Check for image
        if($request->hasFile('company_logo')){

            //Delete old logo
            Storage::delete('public/logos/' . basename($job->company_logo));

            //Store file and get path
            $path = $request->file('company_logo')->store('logos','public');

            // send image path to database 
            $validateData['company_logo'] = $path;
        }

        //Submit to database
        $job->update($validateData);

        return redirect()->route('jobs.index')->with('success','Job listing Updated successfully!');
    }

    // @desc  Delete a job
    // @route DELETE /jobs/{id}
    public function destroy(Job $job)
    {

        //if logo then delete it 
        if($job->company_logo){
            Storage::delete('public/logos/' . $job->company_logo);
        }

        $job->delete();

        return redirect()->route('jobs.index')->with('success','Job listing deleted successfully!');
    }
}