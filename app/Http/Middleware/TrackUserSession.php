<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as ServerRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class TrackUserSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $userAgent = ServerRequest::header('User-Agent');

        // ✅ Skip bots first
        if ($this->isBot($userAgent)) {
            return $next($request);
        }

        // ✅ Cache schema check (Octane + FPM safe)
        $checkedTable = Cache::rememberForever('schema_check_user_sessions', function () {
            return Schema::hasTable('user_sessions');
        });

        if (! $checkedTable) {
            return $next($request);
        }

        $hasVisitedUrl = Cache::rememberForever('schema_check_user_sessions_visited_url', function () {
            return Schema::hasColumn('user_sessions', 'visited_url');
        });

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

        if ($hasVisitedUrl) {
            $data['visited_url'] = $visitedUrl;
        }

        DB::table('user_sessions')->upsert(
            $data,
            ['sessions'],
            ['updated_at']
        );

        return $next($request);
    }

    /**
     * Determine if the User-Agent indicates a bot or crawler.
     */
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