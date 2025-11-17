<?php

namespace App\Services;

use App\Models\UserSessions;
use App\Models\UserPromotions;
use Carbon\Carbon;

class PromotionService
{
    private array $sessionCache = [];


    private function getCachedSession(string $sessionId)
    {
        if (!isset($this->sessionCache[$sessionId])) {
            $this->sessionCache[$sessionId] = UserSessions::where('sessions', $sessionId)
                ->with('promotions')
                ->first();
        }

        return $this->sessionCache[$sessionId];
    }



    public function getActiveCounterPromotion()
    {
        if (!app()->has('global_promotion_on') || app('global_promotion_on') !== "true") {
            return collect();
        }

        return collect(app('promotions'))->filter(function ($promotion) {
            return isset($promotion['start_date'], $promotion['end_date'], $promotion['type']) &&
                $promotion['start_date'] <= now(config('app.timezone'))->format('Y-m-d') &&
                $promotion['end_date'] >= now(config('app.timezone'))->format('Y-m-d') &&
                $promotion['type'] === 'counter';
        });
    }


    public function initializePromotionForSession(string $sessionId)
    {
        $promotionCollection = $this->getActiveCounterPromotion();
        if ($promotionCollection->isEmpty()) {
            return;
        }

        $promotion = $promotionCollection->first();

        $cookieId   = $promotion['cookieid'];
        $cooldown   = $promotion['cooldown_timer'];
        $expiration = now(config('app.timezone'))->addMinutes($cooldown);

        $user = $this->getCachedSession($sessionId);
        if (!$user) {
            return;
        }

        $existingCookieId = request()->cookie('pcid');

        $existingPromotion = optional($user->promotions)
            ->where('promotion_type', 'counter')
            ->first();

        if (!$existingCookieId || $existingCookieId !== $cookieId) {
            if ($existingPromotion) {
                if ($existingPromotion->promotion_cookieid !== $cookieId) {
                    $existingPromotion->update([
                        "promotion_cookieid"        => $cookieId,
                        "promotion_start_date"      => now(config('app.timezone')),
                        "promotion_cooldown_timer"  => $cooldown,
                        "promotion_expiration_date" => $expiration,
                        "promotion_value"           => $promotion['promotion_value'],
                        "promotion_percent"         => $promotion['promotion_percent'],
                    ]);
                }
            } else {
                $this->createPromotion($user->id, $promotion, $cookieId, $cooldown, $expiration);
            }

            cookie()->queue('pcid', $cookieId, (app('global_cookie_max_ages') ?? 30) * 60);
        } elseif (!$existingPromotion) {
            $this->createPromotion($user->id, $promotion, $cookieId, $cooldown, $expiration);
        }
    }


    private function createPromotion($userId, $promotion, $cookieId, $cooldown, $expiration)
    {
        UserPromotions::create([
            "session_id"                => $userId,
            "promotion_id"              => $promotion['id'],
            "promotion_type"            => $promotion['type'],
            "promotion_cookieid"        => $cookieId,
            "promotion_start_date"      => now(config('app.timezone')),
            "promotion_cooldown_timer"  => $cooldown,
            "promotion_expiration_date" => $expiration,
            "promotion_value"           => $promotion['promotion_value'],
            "promotion_percent"         => $promotion['promotion_percent'],
            "active"                    => true
        ]);
    }


    public function getRemainingTime(string $sessionId): int
    {
        $user = $this->getCachedSession($sessionId);
        if (!$user) {
            return 0;
        }

        $promotion = optional($user->promotions)
            ->where('promotion_type', 'counter')
            ->first();

        if (!$promotion || !$promotion->promotion_expiration_date) {
            return 0;
        }

        $expiration = Carbon::parse($promotion->promotion_expiration_date);
        $now = Carbon::now(config('app.timezone'));

        return $expiration->greaterThan($now)
            ? $expiration->diffInSeconds($now)
            : 0;
    }
}
