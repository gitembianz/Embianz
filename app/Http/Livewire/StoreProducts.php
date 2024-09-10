<?php

namespace App\Http\Livewire;

use App\Models\Specs;
use App\Models\Product;
use Livewire\Component;

use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Product_Spec;
use Livewire\WithPagination;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount;
  public $search = "";
  public $quantity;
  public $session_id;
  public $specification;
  public $orderBy = 'best_selling';
  public $category;
  public $category_details;
  public $specfilter = false;
  public $showspecfilter = false;
  public $selectedSpecValues = [];
  public $selectedKeys = [];
  public $selectedSpecNames = [];
  public $productCount;
  public $wishlistItems;


  public function render()
  {
    return view('livewire.store-products', [
      'products' => $this->products,
      'filtervalues' => $this->filtervalues
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

  public function mount($category = null)
  {
    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
    $this->specification = Specs::get();
    if ($category) {
      $decodedCategory = json_decode(htmlspecialchars_decode($category), true);
      $this->category = Category::select('id', 'name', 'short_description', 'long_description', 'seo_id', 'accepted_items')->find($decodedCategory['id']);
    } else {
      if (app()->has('global_default_category')) {
        $this->category = Category::select('id', 'name', 'short_description', 'long_description', 'seo_id', 'accepted_items')->find(app('global_default_category')) ?? null;
      }
    }
    $filteredValues = session()->get('filtered_values', []);
    if (isset($filteredValues['category_id']) && $this->category != null && $filteredValues['category_id'] == $this->category->id) {
      if (isset($filteredValues['selectedSpecValues'])) {
        $this->selectedSpecValues = $filteredValues['selectedSpecValues'];
        $this->applyFilter();
      }
      if (isset($filteredValues['loadAmount'])) {
        $this->loadAmount = $filteredValues['loadAmount'];
      }
    } else {
      session()->forget('filtered_values');
      $this->loadAmount = app('global_limit_load');
    }
  }


  // start filter-spec function
  public function getFilterValuesProperty()
  {
    $query = Product_Spec::select('value', 'spec_id')
      ->groupBy('spec_id', 'value')
      ->with(['spec' => function ($query) {
        $query->select('id', 'name', 'sequence');
      }]);
    $query->whereHas(
      'spec',
      function ($query) {
        $query->where('mark_as_filter', true);
      }
    );
    $query->whereHas(
      'product',
      function ($query) {
        $query->where('active', true)
          ->where('type', '!=', 'parent')
          ->where('start_date', '<=',  now()->format('Y-m-d'))
          ->where('end_date', '>=',  now()->format('Y-m-d'));
      }
    );

    if ($this->category != null) {
      $query->whereHas('product.product_categories', function ($query) {
        $query->where('category_id', $this->category->id);
      });
    }

    return $query->get();
  }


  // aply filter sistem
  public function resetFilter()
  {
    $this->selectedSpecValues = [];
    $this->selectedSpecNames = [];
    $this->selectedKeys = [];
    $this->specfilter = false;
    session()->forget('filtered_values');
  }
  public function applyFilter()
  {
    $this->selectedSpecNames = [];

    $filteredValues = array_map(function ($values) {
      return array_filter($values, function ($value) {
        return $value === true;
      });
    }, $this->selectedSpecValues);
    $allKeys = array_keys(array_merge(...array_values($filteredValues)));

    $this->selectedKeys = $allKeys;
    foreach ($this->specification as $spec) {
      foreach ($this->selectedKeys as $key) {
        foreach ($spec->product_spec as $value) {
          $key = str_replace('_', '.', $key);

          if ($value->value == $key) {
            $this->selectedSpecNames[$key] = $spec->name;
          }
        }
      }
    }

    if (isset($this->selectedKeys)) {
      $this->specfilter = true;
    }
    session()->put('filtered_values', [
      'category_id' => $this->category->id,
      'selectedSpecValues' => $this->selectedSpecValues
    ]);
  }
  public function removeSpec($key)
  {
    $key = str_replace('.', '_', $key);

    foreach ($this->selectedSpecValues as &$subarray) {
      if (isset($subarray[$key])) {
        unset($subarray[$key]);
        if (empty($subarray)) {
          unset($subarray);
        }
        break;
      }
    }
    $key = str_replace('_', '.', $key);
    unset($this->selectedSpecNames[$key]);
    $allKeys = array_keys(array_merge(...$this->selectedSpecValues));
    session()->put('filtered_values', [
      'category_id' => $this->category->id,
      'selectedSpecValues' => $this->selectedSpecValues
    ]);
    $this->selectedKeys = $allKeys;
  }
  public function clearall()
  {
    $this->selectedSpecValues = [];
    $this->selectedSpecNames = [];
    $this->selectedKeys = [];
    $this->specfilter = false;
    session()->forget('filtered_values');
  }

  public function isInWishlist($productId)
  {
    return in_array($productId, $this->wishlistItems);
  }

  // products function
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

    if ($this->specfilter && !empty($this->selectedSpecValues)) {
      $query->where(function ($mainQuery) {
        foreach ($this->selectedSpecValues as $values) {
          $mainQuery->where(function ($specSubQuery) use ($values) {
            foreach ($values as $value => $isSelected) {
              if ($isSelected) {
                $specSubQuery->orWhereHas('product_specs', function ($query) use ($value) {
                  $key = str_replace('_', '.', $value);
                  $query->where('value', $key);
                });
              }
            }
          });
        }

        // Apply the filter to variants if the product type is 'parent'
        $mainQuery->orWhere(function ($parentQuery) {
          $parentQuery->where('type', 'parent')
            ->whereHas('variants.product', function ($variantQuery) {
              foreach ($this->selectedSpecValues as $values) {
                $variantQuery->where(function ($specSubQuery) use ($values) {
                  foreach ($values as $value => $isSelected) {
                    if ($isSelected) {
                      $specSubQuery->orWhereHas('product_specs', function ($query) use ($value) {
                        $key = str_replace('_', '.', $value);
                        $query->where('value', $key);
                      });
                    }
                  }
                });
              }
            });
        });
      });
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
    $query->orderBy('id', 'desc');
    $products = $query->paginate($this->loadAmount);

    return $products;
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
}