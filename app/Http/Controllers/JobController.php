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

    $attributes['featured'] = $request->has('featured');
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

    if ($request->input('featured')) {
        return redirect()->route('payment-page', [
            'amount' => 10,
            'description' => 'Featured Job Post',
        ]);
    }

    return redirect('/')->with('success', 'Job posted successfully!');
}

}


    // public function store(Request $request)
    // {
    //     $attributes = $request->validate([
    //         'title' => ['required'],
    //         'salary' => ['required'],
    //         'location' => ['required'],
    //         'schedule' => ['required', Rule::in(['Part Time', 'Full Time'])],
    //         'url' => ['required', 'active_url'],
    //         'tags' => ['nullable', 'string'],
    //     ]);

    //     $attributes['featured'] = $request->has('featured');
    //     $attributes['employer_id'] = Auth::user()->employer->id;
    //     $attributes['employer_name'] = Auth::user()->employer->name;

    //     $job = Auth::user()->employer->jobs()->create(Arr::except($attributes, 'tags'));

    //     if (!empty($attributes['tags'])) {
    //         foreach (explode(',', $attributes['tags']) as $tag) {
    //             $job->tag($tag);
    //         }
    //     }

    //  // Notify the employer about the job posting
    //     //dd($job);
    //     $user = Auth::user();
    //     $name = $user->name;
    //     $email = $user->email;
    //     $salary = $job->salary;
    //     $title = $job->title;
    //     $url = $job->url;
    //     $schedule = $job->schedule;
    //     $location = $job->location;
        
    
            
       
    //     try {

    //         //Mail::to($job->employer->user)->send(new JobPosted($user, $job));
    //         Mail::to($user->email)->send(new JobPosted($salary, $title, $url, $schedule, $location));
    //         Log::info('Job posting email sent to ' . $user->email);
    //     } catch (\Exception $e) {
    //         Log::error('Failed to send job posting email: ' . $user->email . ' Error: ' . $e->getMessage());   

    //     }

    //     // Handle featured job redirection to payment
    //     if ($request->input('featured')) {
    //         return redirect()->route('payment-page', [
    //             'amount' => 10,
    //             'description' => 'Featured Job Post',
    //         ]);
    //     }

    //     return redirect('/')->with('success', 'Job posted successfully!');
    // }
