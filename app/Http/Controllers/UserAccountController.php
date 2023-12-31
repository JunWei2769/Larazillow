<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    public function create() {
        return inertia('UserAccount/Create');
    }

    public function store(Request $request) {
        $user = User::create($request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',       // check whether the email already exist
            // When using 'confirmed' validator, Laravel would expect another data field called 'password_confirmation'
            'password' => 'required|min:8|confirmed'
        ]));
        // $user->save();      // Must save the user first before login
        Auth::login($user);
        event(new Registered($user));

        return redirect()->route('listing.index')
            ->with('success', 'Account created!');
    }
}
