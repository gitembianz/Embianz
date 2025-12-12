<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheableResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Set cache-friendly headers
        return $response->header('Cache-Control', 'public, max-age=600, s-maxage=600');
    }
}