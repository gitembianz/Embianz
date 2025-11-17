<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Category;
use App\Models\UserPromotions;
use App\Models\UserSessions;

class StoreHeader extends Component
{
  public $session_id;
  public $timer = null;
  public $staticpages;




  private function getSessionId()
  {
    $cookieSessionId = request()->cookie('sessionId');

    $sessionId = $cookieSessionId ?: session()->getId();

    $period = app()->has('global_cookie_max_ages') ? app('global_cookie_max_ages') : 30;

    cookie()->queue(cookie()->make('sessionId', $sessionId, 60 * 24 * $period));

    return $sessionId;
  }



  public function render()
  {
    $data = [
      'categories' => $this->categories

    ];
    return view('livewire.store-header', $data);
  }

  public function mount()
  {
    $this->session_id = $this->getSessionId();
    $this->staticpages = collect(app('static_pages'))->where('display_in_footer', true)->values();
    $counterpromo = $this->promotion->first();
    if (!$counterpromo) {
      return;
    }

    $period = app()->has('global_cookie_max_ages') ? app('global_cookie_max_ages') : 30;

    if ($counterpromo['cookieid']) {
      $promotionCookieId = $counterpromo['cookieid'];
      $existingCookieId = request()->cookie('pcid');
      $promotionCooldown = $counterpromo['cooldown_timer'];
      $expirationDate = now(config('app.timezone'))->addMinutes($promotionCooldown);

      $user = UserSessions::where('sessions', $this->session_id)->first();

      if (!$user) {
        return;
      }

      $existingPromotion = optional($user->promotions)
        ->where('promotion_type', 'counter')
        ->first();

      if (!$existingCookieId || $existingCookieId !== $promotionCookieId) {
        if ($existingPromotion) {
          if ($existingPromotion->promotion_cookieid !== $promotionCookieId) {
            $existingPromotion->update([
              "promotion_cookieid" => $promotionCookieId,
              "promotion_start_date" => now(config('app.timezone')),
              "promotion_cooldown_timer" => $promotionCooldown,
              "promotion_expiration_date" => $expirationDate,
              "promotion_value" => $counterpromo['promotion_value'],
              "promotion_percent" => $counterpromo['promotion_percent'],
            ]);
          }
        } else {
          $this->createPromotion($user->id, $counterpromo, $promotionCookieId, $promotionCooldown, $expirationDate);
        }

        cookie()->queue('pcid', $promotionCookieId, $period * 60);
      } elseif (!$existingPromotion) {
        $this->createPromotion($user->id, $counterpromo, $promotionCookieId, $promotionCooldown, $expirationDate);
      }
    }
  }
  /**
   * Create a new promotion record in the database.
   *
   * @param int $userId
   * @param array $counterpromo
   * @param string $promotionCookieId
   * @param int $promotionCooldown
   * @param \Illuminate\Support\Carbon $expirationDate
   * @return void
   */
  private function createPromotion($userId, $counterpromo, $promotionCookieId, $promotionCooldown, $expirationDate)
  {
    UserPromotions::create([
      "session_id" => $userId,
      "promotion_id" => $counterpromo['id'],
      "promotion_type" => $counterpromo['type'],
      "promotion_cookieid" => $promotionCookieId,
      "promotion_start_date" => now(config('app.timezone')),
      "promotion_cooldown_timer" => $promotionCooldown,
      "promotion_expiration_date" => $expirationDate,
      "promotion_value" => $counterpromo['promotion_value'],
      "promotion_percent" => $counterpromo['promotion_percent'],
      "active" => true
    ]);
  }

   public function getPromotionProperty()
  {
    if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {

      return collect(app()->make('promotions'))
        ->filter(function ($promotion) {
          return isset($promotion['start_date'], $promotion['end_date'], $promotion['type']) && // Ensure keys exist
            $promotion['start_date'] <= now(config('app.timezone'))->format('Y-m-d') &&
            $promotion['end_date'] >= now(config('app.timezone'))->format('Y-m-d') &&
            $promotion['type'] === 'counter';
        });
    } else {
      return collect();
    }
  }

  protected function applyCategoryConditions($query)
  {
    $query->where('active', 1)
      ->where('store_tab', 1)
      ->where('start_date', '<=', now(config('app.timezone'))->format('Y-m-d'))
      ->where('end_date', '>=', now(config('app.timezone'))->format('Y-m-d'))
      ->orderBy('sequence');
  }
  public function getCategoriesProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      return app()->make('cached_categories')->where('store_tab', 1)
        ->where('has_parent', 0)->sortBy('sequence')->take(app('global_limit_category'));
    } else {
      return Category::select('id', 'name', 'seo_id', 'sequence')
        ->with([
          'media' => function ($query) {
            $query->where('type', 'min')->select('media_id', 'path', 'name', 'type');
          },
          'subcategory' => function ($query) {
            $query->whereHas('category', function ($query) {
              $this->applyCategoryConditions($query);
            })->with([
              'category' => function ($query) {
                $query->select('id', 'name', 'seo_id', 'sequence');
                $this->applyCategoryConditions($query);
                $query->with([
                  'media' => function ($query) {
                    $query->where('type', 'min')->select('media_id', 'path', 'name', 'type');
                  },
                  'subcategory' => function ($query) {
                    $query->whereHas('category', function ($query) {
                      $this->applyCategoryConditions($query);
                    })->with([
                      'category' => function ($query) {
                        $query->select('id', 'name', 'seo_id', 'sequence');
                        $this->applyCategoryConditions($query);
                        $query->with([
                          'media' => function ($query) {
                            $query->where('type', 'min')->select('media_id', 'path', 'name', 'type');
                          }
                        ]);
                      }
                    ]);
                  }
                ]);
              }
            ]);
          }
        ])
        ->where('active', 1)
        ->where('store_tab', 1)
        ->where('has_parent', 0)
        ->where('start_date', '<=', now(config('app.timezone'))->format('Y-m-d'))
        ->where('end_date', '>=', now(config('app.timezone'))->format('Y-m-d'))
        ->orderBy('sequence')
        ->limit(app('global_limit_category'))
        ->get();
    }
  }

}
