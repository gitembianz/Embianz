<?php

namespace App\Http\Livewire;


use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;


class StoreShowProduct extends Component
{
  public $productId;
  public $session_id;
  public $back = false;
  public $wishlistItems;
  public $lastVisited;


  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product,
      'last_visited_products' => $this->lastproduct
    ]);
  }
  public function mount($productId)
  {
    $this->productId = $productId;
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();

    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();

    $this->lastVisited = json_decode(request()->cookie('last_visited_products', '[]'), true);
    if (($key = array_search($productId, $this->lastVisited)) !== false) {
      unset($this->lastVisited[$key]);
    }
    array_unshift($this->lastVisited, $productId);
    cookie()->queue(cookie()->make('last_visited_products', json_encode($this->lastVisited), 60 * 24 * 30));
  }

  public function getLastProductProperty()
  {
    if ($this->lastVisited) {
      return Product::whereIn('id', $this->lastVisited)
        ->orderByRaw("FIELD(id, " . implode(',', $this->lastVisited) . ")")
        ->where('active', 1)
        ->where('id', '!=', $this->productId)
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
        ])
        ->get() ?? collect();
    } else {
      return collect();
    }
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
          'product_categories' => function ($query) {
            $query->select('product_id', 'category_id');
            $query->with(['category' => function ($query) {
              $query->select('id', 'name', 'short_description', 'seo_id');
            }]);
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
