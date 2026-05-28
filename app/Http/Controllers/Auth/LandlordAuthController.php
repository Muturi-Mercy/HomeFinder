<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Landlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class LandlordAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.landlord-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:landlords,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        Landlord::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('landlord.login')
            ->with('success', 'Account created! Please login.');
    }

    public function showLogin()
    {
        return view('auth.landlord-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('landlord')->attempt($credentials, $request->remember)) {
            return redirect()->route('landlord.dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        Auth::guard('landlord')->logout();
        return redirect()->route('landlord.login');
    }
}
