<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\SpecificationCacheService;

class LoadSpecificationsCache
{
    public static ?array $specs = null;

    public function handle(Request $request, Closure $next)
    {
        if (self::$specs === null) {
            $service = app(SpecificationCacheService::class);
            self::$specs = $service->getSpecs();
        }

        view()->share('cachedSpecifications', self::$specs);
        app()->instance('cached_specifications', self::$specs);

        return $next($request);
    }
}
