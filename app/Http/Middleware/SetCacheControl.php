<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetCacheControl
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Set cache control headers
        $response->header('Cache-Control', 'max-age=31536000');

        return $response;
    }
}