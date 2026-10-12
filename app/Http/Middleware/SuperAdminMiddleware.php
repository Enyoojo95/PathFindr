<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('super_admin')->check()) {
            return redirect()->route('super-admin.login')
                ->with('error', 'Please login to access the master panel.');
        }

        return $next($request);
    }
}
