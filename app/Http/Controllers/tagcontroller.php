<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tag;

class tagcontroller extends Controller
{
    public function __invoke(tag $tag)
   
    {
       //dd('i love u');
       return view ('results', ['jobs' => $tag->jobs]);
      
    }
}
