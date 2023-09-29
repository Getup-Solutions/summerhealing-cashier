<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(){
        return Inertia::render('Public/Auth/Login');
    }

    public function auth(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($attributes)) {
            $request->session()->regenerate();
            $user = Auth::getProvider()->retrieveByCredentials($attributes);
            Auth::guard('web')->login($user, $request->get('remember'));
            if(Auth::guard('web')->user()->can('admin')){
                return redirect()->intended('/')->with('success', 'Welcome, ' . $user->full_name);             
            }
            return redirect()->intended('/admin/dashboard')->with('success', 'Welcome, ' . $user->full_name);
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
