<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;

class StoreMain extends Component
{
  public $session_id;
  public $wishlistItems;

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

  public function isInWishlist($productId)
  {
    return in_array($productId, $this->wishlistItems);
  }

  public function getPopProductsProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      return app()->make('cached_products')->filter(function ($product) {
        return $product->type != 'parent';
      })->sortByDesc('popularity')->sortByDesc('innerid')->take(app('global_limit_slideritems'));
    } else {
      return Product::with([
        'media' => function ($query) {
          $query->select('path', 'name', 'type')->where('type', 'main');
        },
        'reviews' => function ($query) {
          $query->select('product_id', 'count', 'value');
        },
        'product_prices' => function ($query) {
          $query->select('product_id', 'value', 'discount', 'value_no_discount');
        },
        'product_categories' => function ($query) {
          $query->select('product_id', 'category_id', 'primary_category')
            ->where('primary_category', true);
          $query->with(['category' => function ($query) {
            $query->select('id', 'short_description', 'seo_id');
          }]);
        }
      ])
        ->select('id', 'end_date', 'innerid', 'name', 'seo_id', 'ean', 'quantity', 'sku', 'long_description', 'brand', 'type', 'short_description', 'popularity')
        ->where('active', true)
        ->where('type', '!=', 'parent')
        ->where('start_date', '<=', now()->format('Y-m-d'))
        ->where('end_date', '>=', now()->format('Y-m-d'))
        ->orderBy('popularity', 'DESC')
        ->orderBy('innerid', 'ASC')
        ->limit(app('global_limit_slideritems'))
        ->get();
    }
  }

  public function getNewProductsProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      return app()->make('cached_products')
        ->filter(function ($product) {
          return $product->type != 'parent' && $product->is_new == true;
        })
        ->sortByDesc('popularity')
        ->sortByDesc('innerid')
        ->take(app('global_limit_slideritems'));
    } else {
      return Product::with([
        'media' => function ($query) {
          $query->select('path', 'name', 'type')->where('type', 'main');
        },
        'reviews' => function ($query) {
          $query->select('product_id', 'count', 'value');
        },
        'product_prices' => function ($query) {
          $query->select('product_id', 'value', 'discount', 'value_no_discount');
        },
        'product_categories' => function ($query) {
          $query->select('product_id', 'category_id', 'primary_category')
            ->where('primary_category', true);
          $query->with(['category' => function ($query) {
            $query->select('id', 'short_description', 'seo_id');
          }]);
        }

      ])
        ->select('id', 'end_date', 'innerid', 'name', 'seo_id', 'ean', 'quantity', 'sku', 'long_description', 'brand', 'short_description', 'popularity')
        ->where('active', true)
        ->where('type', '!=', 'parent')
        ->where('start_date', '<=',  now()->format('Y-m-d'))
        ->where('end_date', '>=',  now()->format('Y-m-d'))
        ->where('is_new', true)
        ->orderBy('popularity', 'DESC')
        ->orderBy('innerid', 'ASC')
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
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();
    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
  }
}
