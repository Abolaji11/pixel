<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class RegisterUserController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        //dd($request->all());
        $userAttributes = $request->validate([
            
            'name' => ['required', 'string'],
            
            'email' => ['required', 'email', ],
            'password' => ['required', 'min:8'],
        ]); 

         //dd($userAttributes);
         $employerAttributes = request()->validate([
           'employer' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'mimes:png,jpg,jpeg'],
         ]);

        // Create the user
        $user = User::create($userAttributes);

        // Store the logo and get the path
        $logoPath = $request->file('logo')->store('logos');

        // Create the employer with the user relationship
       $user->employer()->create([
           'name' => $employerAttributes['employer'],
           'logo' => $logoPath,
        ]);

        
        // Log the user in
        auth()->login($user);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Code to show a specific resource
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Code to show the edit form for a specific resource
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Code to update a specific resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Code to delete a specific resource
        auth()->logout();

        return redirect('/');
    }
}
