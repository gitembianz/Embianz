<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreSessionIdInCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the sessionId cookie exists
        if (!array_key_exists('sessionId', $_COOKIE)) {
            $sessionId = session()->getId();

            // Set the sessionId cookie
            setrawcookie('sessionId', $sessionId, [
                'expires' => time() + 30 * 24 * 60 * 60, // 30 days
                'path' => '/',
                'secure' => false, // Set to true if using HTTPS
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        return $next($request);
    }
}