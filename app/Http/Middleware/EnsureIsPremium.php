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
     * @param Closure(Request): (Response) $next
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = $request->user();

        if (!$user || !$user->hasPremiumAccess()) {

            return redirect()
                ->route('dashboard')
                ->with(
                    'error',
                    'This is a Premium Feature.'
                );
        }

        return $next($request);
    }
}