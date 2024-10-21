<?php

namespace App\Http\Livewire;


use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;


class StoreShowProduct extends Component
{
  public $productId;
  public $quantity;
  public $session_id;
  public $back = false;
  public $wishlistItems;

  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product
    ]);
  }
  private function getSessionId()
  {
    if (array_key_exists('sessionId', $_COOKIE)) {
      return $_COOKIE['sessionId'];
    } else {
      return session()->getId();
    }
  }
  public function mount($productId)
  {
    $this->productId = $productId;
    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
  }
  public function isInWishlist($productId)
  {
    return in_array($productId, $this->wishlistItems);
  }

  public function getProductProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
      $cachedProduct = app()->make('cached_products')->firstWhere('id', $this->productId);

      if ($cachedProduct) {
        $cachedProduct->media = collect($cachedProduct->media)->filter(function ($media) {
          return in_array($media['type'], ['full', 'original']);
        })->sortBy('sequence')->values();

        return $cachedProduct;
      }
    } else {

      return Product::select('id', 'name', 'seo_id', 'long_description')
        ->with([
          'media' => function ($query) {
            $query->select('name', 'path', 'type', 'sequence')
              ->whereIn('type', ['full', 'original'])
              ->orderBy('sequence');
          },
          'reviews' => function ($query) {
            $query->select('product_id', 'count', 'value');
          },
          'product_specs' => function ($query) {
            $query->select('product_id', 'spec_id', 'value', 'id')->with('spec:id,name');
          },
          'related_product' => function ($query) {
            $query->select('product_id', 'id', 'parent_id')
              ->orderBy('sequence', 'desc')
              ->orderByRaw('(SELECT innerid FROM products WHERE products.id = product_id) DESC')
              ->with(['product' => function ($query) {
                $query->where('active', 1)
                  ->where('start_date', '<=', now()->format('Y-m-d'))
                  ->where('end_date', '>=', now()->format('Y-m-d'))
                  ->select('id', 'name', 'sku', 'long_description', 'brand', 'popularity', 'seo_id', 'short_description', 'quantity', 'active', 'end_date', 'start_date')
                  ->with([
                    'media' => function ($query) {
                      $query->select('path', 'name', 'type')->where('type', 'main');
                    },
                    'reviews' => function ($query) {
                      $query->select('product_id', 'count', 'value');
                    },
                    'product_categories' => function ($query) {
                      $query->select('product_id', 'category_id', 'primary_category')
                        ->where('primary_category', true);
                      $query->with(['category' => function ($query) {
                        $query->select('id', 'short_description', 'seo_id');
                      }]);
                    },
                    'product_prices' => function ($query) {
                      $query->select('product_id', 'value', 'discount', 'value_no_discount');
                    },
                  ]);
              }]);
          }
        ])
        ->where('id', $this->productId)
        ->first();
    }
  }
}