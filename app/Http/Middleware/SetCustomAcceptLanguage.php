<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCustomAcceptLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Accept-Language', 'en-US, en;q=0.9, ro;q=0.8');

        return $response;
    }
}
