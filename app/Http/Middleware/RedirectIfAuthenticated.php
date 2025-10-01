<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards)
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {

            // If user is accessing login/register normally
            // AND this request is NOT from logo, redirect to HOME
            if (($request->is('login') || $request->is('register'))
                && !$request->query('from_logo')) {
                return redirect(RouteServiceProvider::HOME);
            }

        }
    }

    return $next($request);
}

}
