<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // return response()->json($request);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            // return response()->json(auth()->user()->roles);
            if (auth()->user()->roles == 'dishub') {
                return redirect()->route('dashboard');
            } else {
                return back()->with('error', 'Not Have Access');
            }
        }
        return back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login_page');
    }
}
