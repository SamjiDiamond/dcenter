<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LockScreenController extends Controller
{
    public function index(){

        auth()->user()->lockScreen();
        
        return view('lock-screen');
    }

    public function login(Request $request){
        $validated = $this->validate($request,[
            'password' => 'required'
        ]);

        // Verify the entered password
        $user = auth()->user(); // Get the authenticated user

        if ($user && Hash::check($validated['password'], $user->password)) {
            $user->unlockScreen();
            return redirect()->route('dashboard')->with(['success' => 'Login Successful']);
        }

        return redirect()->back()->withErrors(['password' => 'Incorrect password']);
    }
}
