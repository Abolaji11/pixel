<?php

namespace App\Http\Controllers;

use App\Models\User; // Fixed the capitalization of User
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
        $jobs = Job::latest()->with(['employer', 'tags'])->get()->groupBy('featured');

        return view('jobs.index', [
            'jobs' => $jobs[0] ?? collect(),  // Added null coalescing operator to handle empty groups
            'featuredjobs' => $jobs[1] ?? collect(),  // Added null coalescing operator to handle empty groups
            'tags' => Tag::all(),
        ]);
    }

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

      // Redirect to payment page
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
    // Your logic here
    return view('career');
}

public function salary()
{
    // Your logic here
    return view('salary');
}

}
