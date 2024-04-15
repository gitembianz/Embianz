<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCacheHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if the request is for a static asset (e.g., fonts, SVGs)
        if ($this->isStaticAssetRequest($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000');
        }

        return $response;
    }

    protected function isStaticAssetRequest($request)
    {
        // Get the request path
        $path = $request->getPathInfo();

        // Define an array of file extensions for static assets
        $staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf', 'otf'];

        // Get the file extension from the request path
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Check if the file extension is in the array of static extensions
        if (in_array($extension, $staticExtensions)) {
            return true;
        }

        return false;
    }
}