<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

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
