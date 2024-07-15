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

        // Check if the request is for a static asset (CSS, JS, images, etc.)
        if ($this->isStaticAssetRequest($request)) {
            // Set cache headers for static assets
            $response->headers->set('Cache-Control', 'public, max-age=' . 7 * 24 * 60 * 60); // Example: 7 days
        }

        return $response;
    }

    /**
     * Check if the request is for a static asset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isStaticAssetRequest(Request $request): bool
    {
        $path = $request->getPathInfo();
        return preg_match('/\.(css|js|jpg|jpeg|png|gif|ico|woff|woff2|ttf|svg)$/', $path);
    }
}
