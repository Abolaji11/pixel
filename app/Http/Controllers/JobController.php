<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;



class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        $jobs = job::latest()->with([ 'employer', 'tags'])->get()->groupBy('featured');
        // $jobs = job::all()->groupBy('featured'); note: the use of with(['employer', 'tags]) is to avoid eagerloading(N+1) problems
        return view('jobs.index', [
             'jobs' => $jobs[0],
            'featuredjobs' => $jobs[1],
          
           'tags' => Tag::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request attributes
        //dd($request->all());
        $attributes = $request->validate([
            'title' => ['required'],
            'salary' => ['required'],
            'location' => ['required'],
           // 'schedule' => ['required'],
           'schedule' => ['required', Rule::in(['part Time', 'Full Time'])],
            'url' => ['required', 'active_url'],
            'tags' => ['nullable']
        ]);
    
        // Handle the 'featured' attribute
        $attributes['featured'] = $request->has('featured');
        $attributes['employer_id'] = Auth::user()->employer->id;
        $attributes['employer_name'] = Auth::user()->employer->name;


    
        // Create the job
        $job = Auth::user()->employer->jobs()->create(Arr::except($attributes, 'tags'));
    
        // Handle tags if provided
        if (!empty($attributes['tags'])) {
            foreach (explode(',', $attributes['tags']) as $tag) {
                $job->tag($tag);
            }
        }
    
        // Redirect to the homepage
        return redirect('/');
    }

    // Mail::to($job->employer->user)->queue(
      
    //   new jobposted($job));

    // return redirect('/jobs');
    // }
  
    

    /**
     * Display the specified resource.
     */
    public function show(job $job)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function career()
    {
        return view('career');
    }

    public function salary()
    {
        return view('salary');
    }



}



