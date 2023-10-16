<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LockScreenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
          // Check if the user is authenticated and the screen is locked
          if (auth()->check() && auth()->user()->isScreenLocked()) {
            // Redirect to the lock screen page
            return redirect('/lock-screen');
        }

        // Allow access to the requested page
        return $next($request);
    }
}
