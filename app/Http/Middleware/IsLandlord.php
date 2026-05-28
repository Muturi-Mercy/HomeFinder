<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsLandlord
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('landlord')->check()) {
            return redirect()->route('landlord.login')
                ->with('error', 'Please login as landlord.');
        }
        return $next($request);
    }
}