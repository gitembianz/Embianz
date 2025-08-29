<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as ServerRequest;

class TrackUserSession
{
    protected static bool $checkedTable = false;
    protected static bool $hasVisitedUrl = false;
    protected static bool $bootstrapped = false;

    public function handle(Request $request, Closure $next)
    {
        $userAgent = ServerRequest::header('User-Agent');

        // ✅ Early skip for bots
        if ($this->isBot($userAgent)) {
            return $next($request);
        }

        // ✅ Schema checks only once per Octane worker or FPM request
        if (!self::$bootstrapped) {
            self::$checkedTable = Cache::rememberForever('schema_check_user_sessions', function () {
                return Schema::hasTable('user_sessions');
            });

            self::$hasVisitedUrl = self::$checkedTable &&
                Cache::rememberForever('schema_check_user_sessions_visited_url', function () {
                    return Schema::hasColumn('user_sessions', 'visited_url');
                });

            self::$bootstrapped = true;
        }

        if (!self::$checkedTable) {
            return $next($request);
        }

        // ✅ Prepare data
        $sessionId   = $request->cookie('sessionId') ?? Session::getId();
        $ipAddress   = ServerRequest::ip();
        $visitedUrl  = $request->fullUrl();
        $httpReferer = $request->headers->get('referer');

        $data = [
            'sessions'     => $sessionId,
            'created_at'   => now(),
            'updated_at'   => now(),
            'ip_address'   => $ipAddress,
            'user_agent'   => $userAgent,
            'http_referer' => $httpReferer,
        ];

        if (self::$hasVisitedUrl) {
            $data['visited_url'] = $visitedUrl;
        }

        DB::table('user_sessions')->upsert(
            $data,
            ['sessions'],
            ['updated_at']
        );

        return $next($request);
    }

    private function isBot(?string $userAgent): bool
    {
        if (!$userAgent) return false;

        $botKeywords = [
            'bot', 'crawl', 'spider', 'slurp', 'search', 'bingpreview', 'yandex',
            'duckduckgo', 'baidu', 'sogou', 'mediapartners', 'facebookexternalhit',
            'linkedinbot', 'twitterbot', 'whatsapp',
        ];

        foreach ($botKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}