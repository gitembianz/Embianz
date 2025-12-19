<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request as ServerRequest;

class TrackUserSession
{
  protected static bool $checkedTable = false;
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

      self::$bootstrapped = true;
    }

    if (!self::$checkedTable) {
      return $next($request);
    }

    $cookieid = $request->cookie('sessionId');
    $now = Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s');

    if ($cookieid) {
      DB::statement("
        UPDATE user_sessions
        SET updated_at = ?,
            visited_url = ?
        WHERE sessions = ?
    ", [
        $now,
        $request->fullUrl(),
        $cookieid,
      ]);
    } else {
      try {
        $sessionId = Session::getId();

        DB::statement("
        INSERT INTO user_sessions
        (sessions, created_at, updated_at, ip_address, user_agent, http_referer, visited_url)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ", [
          $sessionId,
          $now,
          $now,
          ServerRequest::ip(),
          $userAgent,
          $request->headers->get('referer'),
          $request->fullUrl(),
        ]);
      } catch (\Exception $e) {
        return $next($request);
      }
    }

    return $next($request);
  }

  private function isBot(?string $userAgent): bool
  {
    if (!$userAgent) return false;

    $botKeywords = [
      'bot',
      'crawl',
      'spider',
      'slurp',
      'search',
      'bingpreview',
      'yandex',
      'duckduckgo',
      'baidu',
      'sogou',
      'mediapartners',
      'linkedinbot',
      'twitterbot',
      'whatsapp',
    ];

    foreach ($botKeywords as $keyword) {
      if (stripos($userAgent, $keyword) !== false) {
        return true;
      }
    }

    return false;
  }
}
