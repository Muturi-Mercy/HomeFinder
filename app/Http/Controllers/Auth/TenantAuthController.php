<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TenantAuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
        return redirect()->route('tenant.dashboard');
        }
        return view('auth.tenant-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('tenant.login')
            ->with('success', 'Account created! Please login.');
    }

    
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
        return redirect()->route('tenant.dashboard');
        }
        return view('auth.tenant-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials, $request->remember)) {
            return redirect()->route('tenant.dashboard');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('tenant.login');
    }
}
