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
      $sessionId = $request->cookie('sessionId') ?? Session::getId();
      $ipAddress = ServerRequest::ip();
      $userAgent = ServerRequest::header('User-Agent');
      $visitedUrl = $request->fullUrl();
      $httpReferer = $request->headers->get('referer');

      // Skip bots
      if ($this->isBot($userAgent)) {
        return $next($request);
      }

      // Prepare base data
      $data = [
        'sessions' => $sessionId,
        'created_at' => now(),
        'updated_at' => now(),
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'http_referer' => $httpReferer,
      ];

      // Add visited_url if the column exists
      if (Schema::hasColumn('user_sessions', 'visited_url')) {
        $data['visited_url'] = $visitedUrl;
      }

      DB::table('user_sessions')->upsert(
        $data,
        ['sessions'],
        ['updated_at']
      );
    }

    return $next($request);
  }


  /**
   * Determine if the User-Agent indicates a bot or crawler.
   *
   * @param string|null $userAgent
   * @return bool
   */
  private function isBot(?string $userAgent): bool
  {
    if (is_null($userAgent)) {
      return false;
    }

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
      'mediapartners', // Google AdSense bot
      'facebookexternalhit',
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
