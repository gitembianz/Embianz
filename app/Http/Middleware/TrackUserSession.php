<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as ServerRequest;
use Illuminate\Support\Facades\Schema;

class TrackUserSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Schema::hasTable('user_sessions')) {
            $sessionId = request()->cookie('sessionId') ?? Session::getId();
            $ipAddress = ServerRequest::ip();
            $userAgent = ServerRequest::header('User-Agent');
            $httpReferer = $request->headers->get('referer');
            DB::table('user_sessions')->upsert(
                [
                    'sessions' => $sessionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'http_referer' => $httpReferer,
                ],
                ['sessions'],
                ['updated_at']
            );
        }

        return $next($request);
    }
}
