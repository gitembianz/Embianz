<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;
use Livewire\WithPagination;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount;
  public $search = "";
  public $session_id;
  public $orderBy = 'best_selling';
  public $category;
  public $queryfilters = [];
  public $selectedKeys = [];
  public $selectedfilters = [];
  public $wishlistItems;

  private function getSessionId()
  {
    if (array_key_exists('sessionId', $_COOKIE)) {
      return $_COOKIE['sessionId'];
    } else {
      return session()->getId();
    }
  }

  public function render()
  {
    return view('livewire.store-products', [
      'products' => $this->products,
      'filtervalues' => $this->filtervalues
    ]);
  }

  public function isInWishlist($productId)
  {
    return in_array($productId, $this->wishlistItems);
  }

  public function mount($category = null)
  {
    $this->session_id = $this->getSessionId();
    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
    if ($category) {
      $decodedCategory = json_decode(htmlspecialchars_decode($category), true);
      $this->category = Category::select('id', 'name', 'short_description', 'long_description', 'seo_id', 'accepted_items', 'display_variant_price')->find($decodedCategory['id']);
    } else {
      if (app()->has('global_default_category')) {
        $this->category = Category::select('id', 'name', 'short_description', 'long_description', 'seo_id', 'accepted_items', 'display_variant_price')->find(app('global_default_category')) ?? null;
      }
    }
    if (isset($filteredValues['loadAmount'])) {
      $this->loadAmount = $filteredValues['loadAmount'];
    } else {
      $this->loadAmount = app('global_limit_load');
    }

    $filteredValues = session()->get('filtered_values', []);
    if (isset($filteredValues['category_id']) && $this->category != null && $filteredValues['category_id'] == $this->category->id) {
      if (isset($filteredValues['queryfilters'])) {
        $this->queryfilters = $filteredValues['queryfilters'];
        $this->applyFilter();
      }
    } else {
      session()->forget('filtered_values');
      $this->loadAmount = app('global_limit_load');
    }
  }

  public function loadMore()
  {
    $this->loadAmount += app('global_limit_load');
    if ($this->category != null) {
      session()->put('filtered_values', [
        'category_id' => $this->category->id,
        'loadAmount' =>  $this->loadAmount
      ]);
    } else {
      session()->put('filtered_values', [
        'loadAmount' =>  $this->loadAmount
      ]);
    }
  }

  // products function only for query, not from cache
  public function getProductsProperty()
  {
    $query = Product::search($this->search)
      ->where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->with([
        'variants' => function ($query) {
          $query->with('product');
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
        'media' => function ($query) {
          $query->select('path', 'name')->where('type', 'main');
        },
      ]);

    if ($this->category != null) {
      $query->whereHas('product_categories.category', function ($query) {
        $query->where('id', $this->category->id);
      });
      if ($this->category->accepted_items == 'default') {
        $query->where('type', '!=', 'parent');
      } else {
        $query->where('type', '!=', 'variant');
      }
    }
    switch ($this->orderBy) {
      case 'best_selling':
        $query->orderBy('popularity', 'desc');
        break;
      case 'name_az':
        $query->orderBy('name');
        break;
      case 'name_za':
        $query->orderBy('name', 'desc');
        break;
      case 'date_old_new':
        $query->orderBy('created_at');
        break;
      case 'date_new_old':
        $query->orderBy('created_at', 'desc');
        break;
      case 'quantity_as':
        $query->where('quantity', '>', 0)->orderBy('quantity');
        break;
      case 'quantity':
        $query->where('quantity', '>', 0)->orderBy('quantity', 'desc');
        break;
      case 'price_as':
        $query->orderByRaw("(SELECT CAST(value AS DECIMAL(10, 2)) FROM pricelist_entries WHERE product_id = products.id) asc");
        break;
      case 'price_ds':
        $query->orderByRaw("(SELECT CAST(value AS DECIMAL(10, 2)) FROM pricelist_entries WHERE product_id = products.id) desc");
        break;
    }
    if (!empty($this->selectedKeys)) {
      $query->whereIn('id', $this->selectedKeys);
    }

    return $query->orderBy('id', 'desc')->paginate($this->loadAmount);
  }

  // filters fro cache
  public function getFilterValuesProperty()
  {
    $query = app('cached_specifications');

    if ($this->category != null) {
      $query = collect($query)->map(function ($spec) {
        $filteredValues = collect($spec['values'])->map(function ($value) {
          $filteredProducts = collect($value['products'])->filter(function ($product) {
            return in_array($this->category->id, $product['categories']);
          });

          if ($filteredProducts->isNotEmpty()) {
            $productIds = $filteredProducts->pluck('product_id')->toArray();

            return [
              'product_ids' => $productIds,
            ];
          }
          return null;
        })->filter();

        if ($filteredValues->isNotEmpty()) {
          return [
            'spec' => $spec['spec'],
            'sequence' => $spec['sequence'],
            'values' => $filteredValues->toArray(),
          ];
        }
        return null;
      })->filter();
    }
    return $query;
  }
  public function applyFilter()
  {
    $this->selectedKeys = [];
    $this->selectedfilters = [];
    $productIdsPerSpec = [];

    if (!empty($this->queryfilters)) {

      foreach ($this->queryfilters as $specName => $values) {
        $specProductIds = [];

        foreach ($values as $value => $isfilterselected) {
          if (array_values($isfilterselected)[0]) {
            $productIds = explode(',', array_keys($isfilterselected)[0]);

            $specProductIds = array_merge($specProductIds, $productIds);

            $this->selectedfilters[$value] = $specName;
          }
        }

        $productIdsPerSpec[] = array_unique($specProductIds);
      }

      if (count($productIdsPerSpec) > 1) {
        $this->selectedKeys = array_intersect(...$productIdsPerSpec);
      } else {
        $this->selectedKeys = $productIdsPerSpec[0] ?? [];
      }

      session()->put('filtered_values', [
        'category_id' => $this->category->id,
        'queryfilters' => $this->queryfilters
      ]);
      if (count($this->selectedKeys) > 0) {

        $this->emit('filtersApplied', count($this->selectedKeys));
      } else {
        $this->emit('filtersApplied', $this->products->total());
      }
    }
    return $this->products->whereIn('id', $this->selectedKeys);
  }

  public function clearall()
  {
    $this->queryfilters = [];
    session()->forget('filtered_values');
    $this->applyFilter();
  }

  public function removeSpec($key, $specname)
  {
    unset($this->queryfilters[$specname][$key]);
    if (empty($this->queryfilters[$specname])) {
      unset($this->queryfilters[$specname]);
    }
    if (empty($this->queryfilters)) {
      unset($this->queryfilters);
      session()->forget('filtered_values');
    } else {
      session()->put('filtered_values', [
        'category_id' => $this->category->id,
        'queryfilters' => $this->queryfilters
      ]);
    }
    unset($this->selectedfilters[$key]);
    $this->applyFilter();
  }
}
