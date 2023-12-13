<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if sessionId is present in the cookie
        if (!array_key_exists('sessionId', $_COOKIE)) {

            // If not present, generate a new sessionId
            $sessionId = Session::getId();

            // Set the new sessionId in the cookie
            setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);
        }
        return $next($request);
    }
}
