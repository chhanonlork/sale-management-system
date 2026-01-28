<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ១. បង្ហាញផ្ទាំង Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ២. ធ្វើការ Login (Check Email/Pass)
    public function login(Request $request)
    {
        // Validation
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Login ជោគជ័យ -> ទៅកាន់ Dashboard
            return redirect()->intended('dashboard');
        }

        // Login បរាជ័យ
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // ៣. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}