<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\job;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        //dd($request->query->all());
       $jobs=job::query()
       ->with(['employer', 'tags'])
       ->where('title', 'like', '%'.$request->input('q').'%')
       ->get();

        //dd($jobs);
       //return $jobs;
       return view ('results', ['jobs' => $jobs]);
    }
}
