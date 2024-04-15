<?php

namespace App\Http\Livewire;

use App\Models\Specs;
use App\Models\Product;
use App\Models\Product_Spec;

use Livewire\Component;
use App\Models\Category;
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
      $sessionId = session()->getId();
      setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);
      return $sessionId;
    }
  }

  public function mount($category = null)
  {

    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
    $this->specification = Specs::get();
    if ($category) {
      $decodedCategory = json_decode(htmlspecialchars_decode($category), true);
      $this->category = Category::select('id', 'name', 'long_description', 'seo_id')->find($decodedCategory['id']);
    } else {
      if (app()->has('global_default_category')) {
        $this->category = Category::select('id', 'name', 'long_description', 'seo_id')->find(app('global_default_category')) ?? null;
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
        $query->where('active', true);
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

  // products function
  public function getProductsProperty()
  {
    $query = Product::name($this->search)
      ->where('active', true)
      ->where('start_date', '<=',  now()->format('Y-m-d'))
      ->where('end_date', '>=',  now()->format('Y-m-d'))
      ->with([
        'product_prices',
        'product_prices.pricelist.currency',
        'media' => function ($query) {
          $query->select('path', 'name')->where('type', 'main');
        },
        'wishlists' => function ($query) {
          $query->select('id', 'product_id')->where('session_id', $this->session_id);
        },
      ]);
    if ($this->category != null) {
      $query->whereHas('product_categories.category', function ($query) {
        $query->where('id', $this->category->id);
      });
    }
    if ($this->specfilter && !empty($this->selectedSpecValues)) {
      foreach ($this->selectedSpecValues as $values) {
        $query->Where(function ($specSubQuery) use ($values) {
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