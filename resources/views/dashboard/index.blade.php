<x-layout>
<section class="flex flex-col md:flex-row gap-4">
    <div class="bg-white p-8 rounded-lg shadow-md w-full">
        <h3 class="text-3xl text-center font-bold mb-4">Profile Info</h3>

        @if($user->avatar)
        <div class="mt-2 flex justify-center">
            <img src="{{asset('storage/' . $user->avatar)}}" alt="{{$user->name}}" class="w-32 h-32 object-cover rounded-full" />
        </div>
        @endif

        <form action="{{route('profile.update')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <x-inputs.text id="name" name="name" label="Name" value="{{$user->name}}" />

            <x-inputs.text id="email" name="email" label="Email address" value="{{$user->email}}" />

            <x-inputs.file id="avatar" name="avatar" label="Upload Avatar" />
            
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 border rounded focus:outline-none">Save</button>
        </form>
    </div>

 {{-- Job Listings  --}}
<div class="bg-white p-8 rounded-lg shadow-md w-full">
    <h3 class="text-3xl text-center font-bold mb-4">My Job Listings</h3>
    @forelse($jobs as $job)
    <div class="flex justify-between items-center border-gray-200 py-2">
        <div>
            <h3 class="text-xl font-semibold">{{$job->title}}</h3>
            <p class="text-gray-700">{{$job->job_type}}</p>
        </div>
   
        <div class="flex space-x-3">
            <!-- Edit button  -->
            <a href="{{route('jobs.edit',$job->id)}}" class="bg-blue-500 text-white px-4 py-2 rounded text-sm">Edit</a>
            <!-- Edit button  -->

            <!-- Delete Form -->
            <form method="POST" action="{{route('jobs.destroy',$job->id)}}?from=dashboard" onsubmit="return confirm('Are you sure you want to delete!')">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded text-sm"
                >
                    Delete
                </button>
            </form>
        <!-- End Delete Form -->
        </div>
    </div>

    {{-- Applicants  --}}
    <div class="mt-4 border-b-2 bg-gray-100 p-2">
        <h4 class="text-lg font-semibold mb-2">Applicants for {{$job->title}}</h4>
        @forelse($job->applicants as $applicant)
        <div class="py-2">
            <p class="text-gray-800">
                <strong>Name: </strong> {{$applicant->full_name}}
            </p>

            <p class="text-gray-800">
                <strong>Phone: </strong> {{$applicant->contact_phone}}
            </p>

            <p class="text-gray-800">
                <strong>Email: </strong> {{$applicant->contact_email}}
            </p>

            <p class="text-gray-800">
                <strong>Message: </strong> {{$applicant->message}}
            </p>
            <p class="text-gray-800 mt-2">
                <a href="{{asset('storage/' . $applicant->resume_path)}}" class="text-blue-500 hover:underline text-sm" download>
                    <i class="fas fa-download"></i> Download Resume
                </a>
            </p>
            {{-- Delete Applicant  --}}
            <form action="{{route('applicant.destroy',$applicant->id)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this applicant?')">
                @csrf 
                @method('DELETE')
                <button type="submit" class="text-red-500 text-sm hover:text-red-700">
                    <i class="fas fa-trash"></i> Delete Applicant
                </button>
            </form>
        </div>
        @empty  
        <p class="text-gray-700 mb-5">No applicants for this job</p>
        @endforelse
    </div>

    @empty 
    <p class="text-gray-700">You have not job listings</p>
    @endforelse
</div>

</section>

<div class="bg-white p-8 rounded-lg shadow-md w-full">
    <h3 class="text-3xl text-center font-bold mb-4">Job Applied</h3>
    @forelse($appliedJobs as $appliedJob)
        <div class=" border-gray-200 py-2">
            <div class="border-b-2 p-8 bg-gray-100 flex justify-between items-center ">
                <div>
                    <h3 class="text-xl font-semibold">{{ $appliedJob->title }}</h3>
                    <p class="text-gray-700">{{ $appliedJob->job_type }}</p>
                    <p class="text-gray-700">{{ $appliedJob->requirements }}</p>
                </div>
    
                <div class="flex space-x-3">
                    <!-- Check if the appliedJob has applicants -->
                    @forelse($appliedJob->applicants as $applicant)
                        <!-- Delete Form -->
                        <form method="POST" action="{{ route('applicant.destroy', $applicant->id) }}?from=dashboard" onsubmit="return confirm('Are you sure you want to Cancel!')">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded text-sm"
                            >
                                Cancel
                            </button>
                        </form>
                        <!-- End Delete Form -->
                    @empty
                        <p class="text-gray-700">No applicants found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @empty
        <p class="text-gray-700">You don't have apply job.</p>
    @endforelse
</div>

<x-bottom-banner />
    
</x-layout>