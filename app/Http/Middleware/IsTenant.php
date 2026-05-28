<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsTenant
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('tenant.login')
                ->with('error', 'Please login to continue.');
        }
        return $next($request);
    }
}
