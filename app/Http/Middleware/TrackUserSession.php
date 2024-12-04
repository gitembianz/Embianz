<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as ServerRequest;

class TrackUserSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $sessionId = request()->cookie('sessionId') ?? Session::getId();
        $ipAddress = ServerRequest::ip();
        $userAgent = ServerRequest::header('User-Agent');

        DB::table('user_sessions')->upsert(
            [
                'sessions' => $sessionId,
                'last_activity' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ],
            ['sessions'],
            ['last_activity', 'updated_at']
        );

        return $next($request);
    }
}
