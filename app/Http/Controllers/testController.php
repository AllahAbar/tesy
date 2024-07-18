<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\table_atg_members;


class testController extends Controller

{
    public function show(){
        return view('register');
    }

    public function new(Request $request)
    {
        // Validate form data
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:table_atg_members',
            'password' => 'required|string|min:1',
        ]);

        // Create new user
        $user = new table_atg_members();
        $user->name = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->save();

        // Optionally, you can authenticate the user after registration
        // auth()->login($user);

        // Redirect to a success page or dashboard
        return redirect('/dashboard')->with('success', 'Registration successful!');
    }
}


