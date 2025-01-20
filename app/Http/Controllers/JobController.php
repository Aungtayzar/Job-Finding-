<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class JobController extends Controller
{
    use AuthorizesRequests;

    // @desc   Show all jobs
    // @route  GET /jobs
    public function index()
    {
        
        $jobs = Job::latest()->paginate(9);
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

        //Assign Auth user id 
        $validateData['user_id'] = auth()->user()->id;

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
        $this->authorize('update', $job);
        return view('jobs.edit')->with('job',$job);
         
    }

    // @desc   Update a job
    // @route  PUT /jobs/{id}
    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);

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

        $this->authorize('delete', $job);

        //if logo then delete it 
        if($job->company_logo){
            Storage::delete('public/logos/' . $job->company_logo);
        }

        $job->delete();

        //check if delete from dashboard
        if(request()->query('from') == 'dashboard'){
            return redirect()->route('dashboard')->with('success','Job listing deleted successfully!');
        }

        return redirect()->route('jobs.index')->with('success','Job listing deleted successfully!');
    }

    //@desc Search job listing 
    //@route Get/jobs/search
    public function search(Request $request)
    {
        $keywords = strtolower($request->input('keywords'));
        $location = strtolower($request->input('location'));

        $query = Job::query();

        if($keywords)
        {
            $query->where(function($q) use($keywords){
                $q->whereRaw('LOWER(title) like ?' , ['%' . $keywords . '%'])
                    ->orWhereRaw('LOWER(description) like ?', ['%'.$keywords. '%'])
                    ->orWhereRaw('LOWER(tags) like ?', ['%'.$keywords. '%']);
            });
        }

        if($location)
        {
            $query->where(function($q) use($location){
                $q->whereRaw('LOWER(address) like ?' , ['%' . $location . '%'])
                    ->orWhereRaw('LOWER(city) like ?', ['%'.$location. '%'])
                    ->orWhereRaw('LOWER(state) like ?', ['%'.$location. '%'])
                    ->orWhereRaw('LOWER(zipcode) like ?', ['%'.$location. '%']);
            });
        }

        $jobs = $query->paginate(12);

        return view('jobs.index',compact('jobs'));

    }

}