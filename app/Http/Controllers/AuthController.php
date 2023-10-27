<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function create() {      // display form to sign in
        return inertia('Auth/Login');
    }

    public function store(Request $request) {       // handling the logic when form is submitted
        if (!Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]), true)) {
            throw ValidationException::withMessages([
                'email' => 'Authentication failed'
            ]);
        }

        $request->session()->regenerate();      // regenerate session if authentication is successful

        return redirect()->intended('/listing');        // user will be redirected to the default location if authentication is successful
    }

    public function destroy(Request $request) {     // Authenticated user is being removed by signing out
        Auth::logout();

        $request->session()->invalidate();      // Invalidate the session
        $request->session()->regenerateToken();

        return redirect()->route('listing.index');
    }
}
