<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CookieConsent
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
        if ($request->has('accept_cookies')) {
            // Set a cookie to remember the user's choice
            cookie()->queue('accept_cookies', true, 60 * 24 * 365);
        }
        return $next($request);
    }
}
