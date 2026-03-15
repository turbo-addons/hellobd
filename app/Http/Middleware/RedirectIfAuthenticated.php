<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if ($request->is('login') || $request->is('register') || $request->is('admin/login') || $request->is('admin/register')) {
                return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
            }
        }

        return $next($request);
    }
}
