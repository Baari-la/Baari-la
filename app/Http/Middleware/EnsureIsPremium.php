<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsPremium
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // If the logged-in user is NOT premium, send them to a 'Upgrade' page
    if (auth()->user() && auth()->user()->is_premium !== 1) {
        return redirect()->route('dashboard')->with('error', 'This is a Premium Feature.');
    }

    return $next($request);
}
}