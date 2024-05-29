<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCacheControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Setăm header-ul Cache-Control pentru a stoca cache-ul timp de 30 de zile
        $response->headers->set('Cache-Control', 'public, max-age=' . 30 * 24 * 60 * 60);

        return $response;
    }
}