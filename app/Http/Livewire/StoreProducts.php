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

  public function mount()
  {
    $this->loadAmount = app('global_limit_load');
    $this->quantity = app('global_low_stock');
    $this->specification = Specs::get();
    $this->session_id = $this->getSessionId();
  }

  // start filter-spec function
  public function getFilterValuesProperty()
  {
    $query = Product_Spec::select('value', 'spec_id', 'sequence')
      ->groupBy('spec_id', 'value', 'sequence')->orderby('sequence')
      ->with(['spec' => function ($query) {
        $query->select('id', 'name');
      }]);
    $query->whereHas(
      'product',
      function ($query) {
        $query->where('active', true);
      }
    );

    if ($this->category) {
      $query->whereHas('product.product_categories', function ($query) {
        $query->where('category_id', $this->category);
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
  }
  public function applyFilter()
  {
    $this->selectedSpecNames = [];
    $filteredValues = array_filter($this->selectedSpecValues, function ($values) {
      return in_array(true, $values);
    });
    $allKeys = array_keys(array_merge(...array_values($filteredValues)));
    $this->selectedKeys = $allKeys;
    foreach ($this->specification as $spec) {
      foreach ($this->selectedKeys as $key) {
        foreach ($spec->product_spec as $value) {
          if ($value->value == $key) {
            $this->selectedSpecNames[$key] = $spec->name;
          }
        }
      }
    }

    if (isset($this->selectedKeys)) {
      $this->specfilter = true;
    }
  }
  public function removeSpec($key)
  {

    foreach ($this->selectedSpecValues as &$subarray) {
      if (isset($subarray[$key])) {
        unset($subarray[$key]);
        if (empty($subarray)) {
          unset($subarray);
        }
        break;
      }
    }
    unset($this->selectedSpecNames[$key]);
    $allKeys = array_keys(array_merge(...$this->selectedSpecValues));
    $this->selectedKeys = $allKeys;
  }
  public function clearall()
  {
    $this->selectedSpecValues = [];
    $this->selectedSpecNames = [];
    $this->selectedKeys = [];
    $this->specfilter = false;
  }

  // products function
  public function getProductsProperty()
  {
    $query = Product::name($this->search)->where('active', true)->with([
      'product_prices',
      'product_prices.pricelist.currency',
      'media' => function ($query) {
        $query->select('path', 'name')->where('type', 'main');
      },
      'wishlists' => function ($query) {
        $query->select('id', 'product_id')->where('session_id', $this->session_id);
      }
    ]);
    if ($this->category) {
      $this->category_details = Category::find($this->category, ['name', 'long_description']);
      $query->whereHas('product_categories.category', function ($query) {
        $query->where('id', $this->category);
      });
    }
    if ($this->specfilter && !empty($this->selectedSpecValues)) {
      foreach ($this->selectedSpecValues as $values) {
        $query->Where(function ($specSubQuery) use ($values) {
          foreach ($values as $value => $isSelected) {
            if ($isSelected) {
              $specSubQuery->orWhereHas('product_specs', function ($query) use ($value) {
                $query->where('value', $value);
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
        $query->join('pricelist_entries', 'products.id', '=', 'pricelist_entries.product_id')
          ->orderByRaw('CAST(value AS DECIMAL(10, 2)) asc');
        break;
      case 'price_ds':
        $query->join('pricelist_entries', 'products.id', '=', 'pricelist_entries.product_id')
          ->orderByRaw('CAST(value AS DECIMAL(10, 2)) desc');
        break;
    }
    // Get the count and paginate in a single query
    $products = $query->paginate($this->loadAmount);

    // Set the total count to the property
    $this->productCount = $products->total();

    return $products;
  }

  public function loadMore()
  {
    $this->loadAmount += 16;
  }
}