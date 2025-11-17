<?php

namespace App\Http\Middleware;

use Closure;

class PromotionInit
{
   public function handle($request, Closure $next)
{
    $sessionId = $request->cookie('sessionId') ?? session()->getId();

    app('promotionService')->initializePromotionForSession($sessionId);

    return $next($request);
}
}
