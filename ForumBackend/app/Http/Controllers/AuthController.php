<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Regisztralas(Request $request)
    {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    Auth::login($user);

    return redirect('/dashboard');
    }

    public function Regisztracio()
    {
        return view('auth.regisztracio');
    }

    public function Dashboard()
    {
        $user = Auth::user();
        return view('dashboard', compact());
    }

    public function Login()
    {
        $credentials = $request->only('email','password');

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'A megadott hitelesítő adatok nem egyeznek.',
        ]);
    }

    public function Logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}