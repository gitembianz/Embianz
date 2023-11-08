<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTypeCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has usertype equal to 1
        if (auth()->check() && auth()->user()->usertype == 1) {
            return $next($request);
        }

        // If not authenticated or usertype is not 1, you can redirect or return an unauthorized response
        return redirect('/login')->with('error', 'Unauthorized access');
    }
}
