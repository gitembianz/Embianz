<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use Livewire\Component;
use App\Models\Category;
use App\Models\UserSessions;

class StoreHeader extends Component
{
  public $session_id;
  public $timer = null;

  protected $listeners = [
    'newcart' => 'NewCart',
    'orderprocess' => 'getCartProperty',
  ];

  private function getSessionId()
  {
    $cookieSessionId = request()->cookie('sessionId');

    $sessionId = $cookieSessionId ?: session()->getId();

    $period = app()->has('global_cookie_max_ages') ? app('global_cookie_max_ages') : 30;

    cookie()->queue(cookie()->make('sessionId', $sessionId, 60 * 24 * $period));

    if ($cookieSessionId) {
      DB::table('sessions')
        ->where('id', session()->getId())
        ->update(['innersession' => $cookieSessionId]);
    }

    return $sessionId;
  }



  public function render()
  {
    $data = [
      'categories' => $this->categories,
      'cart' => $this->cart,

    ];
    return view('livewire.store-header', $data);
  }
  public function mount()
  {
    $this->session_id = $this->getSessionId();

    if ($this->promotion) {
      $firstPromotion = $this->promotion->first();

      if ($firstPromotion['cookieid'] && $firstPromotion['cookie_time']) {
        $promotionCookieId = $firstPromotion['cookieid'];

        $existingCookieId = request()->cookie('pcid');

        if (!$existingCookieId || $existingCookieId !== $promotionCookieId) {
          cookie()->queue(
            'pcid',
            $promotionCookieId,
            60 * $firstPromotion['cookie_time'] // Time in minutes
          );

          $promotionCooldown = $firstPromotion['cooldown_timer']; // Timer in minutes
          $expirationDate = now()->addSeconds($promotionCooldown * 60); // Add cooldown in seconds

          UserSessions::where('sessions', $this->session_id)->update([
            "promotion_cookieid" => $promotionCookieId,
            "promotion_start_date" => now(),
            "promotion_cooldown_timer" => $promotionCooldown,
            "promotion_expiration_date" => $expirationDate,
          ]);
        }
      }
    }
  }


  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->latest()
      ->first() ?? null;
  }

  public function NewCart()
  {
    $this->getCategoriesProperty();
    $this->emit('newcartlist');
  }
  protected function applyCategoryConditions($query)
  {
    $query->where('active', 1)
      ->where('store_tab', 1)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
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
        ->where('start_date', '<=', now()->format('Y-m-d'))
        ->where('end_date', '>=', now()->format('Y-m-d'))
        ->orderBy('sequence')
        ->limit(app('global_limit_category'))
        ->get();
    }
  }
  public function getPromotionProperty()
  {
    if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {

      return collect(app()->make('promotions'))
        ->filter(function ($promotion) {
          return isset($promotion['start_date'], $promotion['end_date'], $promotion['type']) && // Ensure keys exist
            $promotion['start_date'] <= now()->format('Y-m-d') &&
            $promotion['end_date'] >= now()->format('Y-m-d') &&
            $promotion['type'] === 'counter';
        });
    } else {
      return collect();
    }
  }
}
