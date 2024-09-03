<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use App\Mail\JobPosted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;



class JobController extends Controller
{
    
    public function index()
{
    // Paginate featured jobs
    $featuredJobs = Job::latest()
        ->where('featured', true)
        ->with(['employer', 'tags'])
        ->paginate(10);

    // Paginate regular jobs
    $regularJobs = Job::latest()
        ->where('featured', false)
        ->with(['employer', 'tags'])
        ->paginate(5);

    $tags = Tag::paginate(10);

    return view('jobs.index', [
        'jobs' => $regularJobs,
        'featuredjobs' => $featuredJobs,
        'tags' => $tags,
    ]);
}


//     public function index()
// {
//     // Fetch all jobs with pagination
//     $jobsQuery = Job::latest()->with(['employer', 'tags']);

//     // Separate featured and regular jobs
//     $featuredJobs = $jobsQuery->where('featured', true)->paginate(10);
//     $regularJobs = $jobsQuery->where('featured', false)->paginate(5);

//     return view('jobs.index', [
//         'jobs' => $regularJobs,
//         'featuredjobs' => $featuredJobs,
//         'tags' => Tag::all(),
//     ]);
// }

    
  

    public function create()
    {
        return view('jobs.create');
    }


    public function store(Request $request)
{
    $attributes = $request->validate([
        'title' => ['required'],
        'salary' => ['required'],
        'location' => ['required'],
        'schedule' => ['required', Rule::in(['Part Time', 'Full Time'])],
        'url' => ['required', 'active_url'],
        'tags' => ['nullable', 'string'],
    ]);

      $isFeatured = $request->has('featured');

    if ($isFeatured) {

    session([
             'job_data' => $attributes,
                'tags' => $attributes['tags']
            ]);

      //return redirect()->route('payment-page')->withInput($request->all());
      return redirect()->route('payment-page', [
               'amount' => 10,
                'description' => 'This is a featured Job Post',
            ]);
         

    }

 // if ($request->input('featured')) {
    //     return redirect()->route('payment-page', [
    //         'amount' => 10,
    //         'description' => 'Featured Job Post',
    //     ]);
    // }

    $attributes['featured'] = false;
    $attributes['employer_id'] = Auth::user()->employer->id;
    $attributes['employer_name'] = Auth::user()->employer->name;

    $job = Auth::user()->employer->jobs()->create(Arr::except($attributes, 'tags'));

    if (!empty($attributes['tags'])) {
        foreach (explode(',', $attributes['tags']) as $tag) {
            $job->tag($tag);
        }
    }

    $user = Auth::user();
    
    try {
        Mail::to($user->email)->send(new JobPosted(
            $job->title,
            $job->salary,
            $job->url,
            $job->schedule,
            $job->location
        ));
        Log::info('Job posting email sent to ' . $user->email);
    } catch (\Exception $e) {
        Log::error('Failed to send job posting email: ' . $user->email . ' Error: ' . $e->getMessage());
    }

   

    return redirect('/')->with('success', 'Job posted successfully!');
}

public function career()
{
    return view('career');
}

public function salary()
{
    return view('salary');
}

}
