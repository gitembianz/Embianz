<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class StoreMain extends Component
{
  public $quantity;
  public $session_id;

  public function getSliderItemsProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      return app()->make('cached_categories')->where('slider_sequence', '!=', '0')->sortBy('slider_sequence');
    } else {
      return Category::select('id', 'slider_sequence', 'seo_id')->where('slider_sequence', '!=', '0')->where('start_date', '<=',  now()->format('Y-m-d'))
        ->where('end_date', '>=',  now()->format('Y-m-d'))->with(['media' => function ($query) {
          $query->select('path', 'name', 'sequence', 'width', 'height')->where('type', 'original');
        }])->orderby('slider_sequence')->get();
    }
  }

  private function getSessionId()
  {
    if (array_key_exists('sessionId', $_COOKIE)) {
      return $_COOKIE['sessionId'];
    } else {
      return session()->getId();
    }
  }

  public function getPopProductsProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      return app()->make('cached_products')->filter(function ($product) {
        return $product->type != 'parrent';
      })->sortByDesc('popularity')->take(app('global_limit_slideritems'));
    } else {
      return Product::with([
        'media' => function ($query) {
          $query->select('path', 'name')->where('type', 'main');
        },
        'product_prices' => function ($query) {
          $query->select('product_id', 'value', 'discount', 'value_no_discount');
        },
        'wishlists' => function ($query) {
          $query->select('id', 'product_id')->where('session_id', $this->session_id);
        }
      ])
        ->select('id', 'name', 'seo_id', 'quantity', 'type', 'short_description', 'popularity')
        ->where('active', true)
        ->where('type', '!=', 'parrent')
        ->where('start_date', '<=',  now()->format('Y-m-d'))
        ->where('end_date', '>=',  now()->format('Y-m-d'))
        ->orderBy('popularity', 'desc')
        ->limit(app('global_limit_slideritems'))
        ->get();
    }
  }

  public function getNewProductsProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      return app()->make('cached_products')
        ->filter(function ($product) {
          return $product->type != 'parrent' && $product->is_new == true;
        })
        ->sortByDesc('popularity')
        ->take(app('global_limit_slideritems'));
    } else {
      return Product::with([
        'media' => function ($query) {
          $query->select('path', 'name')->where('type', 'main');
        },
        'product_prices' => function ($query) {
          $query->select('product_id', 'value', 'discount', 'value_no_discount');
        },
        'wishlists' => function ($query) {
          $query->select('id', 'product_id')->where('session_id', $this->session_id);
        }
      ])
        ->select('id', 'name', 'seo_id', 'quantity', 'short_description', 'popularity')
        ->where('active', true)
        ->where('type', '!=', 'parrent')
        ->where('start_date', '<=',  now()->format('Y-m-d'))
        ->where('end_date', '>=',  now()->format('Y-m-d'))
        ->where('is_new', true)
        ->orderBy('popularity', 'desc')
        ->limit(app('global_limit_slideritems'))
        ->get();
    }
  }



  public function render()
  {
    return view('livewire.store-main', [
      'popproducts' => $this->popproducts,
      'newproducts' => $this->newproducts,

      'slideritems' => $this->slideritems,

    ]);
  }
  public function mount()
  {
    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
  }
}
