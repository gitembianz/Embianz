<?php

namespace App\Http\Livewire;

use App\Models\Specs;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount = 16;
  public $search = "";
  public $quantity = 10;
  public $session_id;
  public $specification;
  public $orderBy = 'name_az'; // Default sorting order
  public $orderAsc = true;
  public $category;
  public $category_details;
  public $property = false;
  public $specfilter = false;
  public $showspecfilter = false;
  public $selectedSpecValues = [];
  public $selectedKeys = [];
  public $selectedSpecNames = [];


  public function render()
  {
    return view('livewire.store-products', ['products' => $this->products]);
  }

  public function mount()
  {
    $this->session_id = isset($_COOKIE['sessionId']) ? $_COOKIE['sessionId'] : session()->getId();
    $this->specification = Specs::with('product_spec')->get();
  }
  // start filter-spec function
  public function getUniqueSpecValues($specId)
  {
    $uniqueValues = [];

    foreach ($this->specification as $spec) {
      if ($spec->id === $specId) {
        foreach ($spec->product_spec as $value) {
          $uniqueValues[] = $value->value;
        }
        break; // Exit the loop once the specific spec is found and processed
      }
    }

    return array_unique($uniqueValues);
  }
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
    // Extract keys where the value is true
    $allKeys = array_keys(array_merge(...$filteredValues));
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
      $this->property = false;
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
    $this->selectedKeys = $allKeys; // Update selectedKeys
  }
  public function clearall()
  {
    $this->selectedSpecValues = [];
    $this->selectedSpecNames = [];
    $this->selectedKeys = [];
    $this->specfilter = false;
  }
  // end filter-spec function

  // Products function
  public function getProductsProperty()
  {
    $query = Product::name($this->search)->where('active', true)->with([
      'product_prices',
      'product_prices.pricelist.currency',
      'media' => function ($query) {
        $query->where('type', 'main'); // Filter and limit the media relationship
      },
      'wishlists' => function ($query) {
        $query->where('session_id', $this->session_id);
      }
    ]);
    if ($this->category) {
      $this->category_details = Category::find($this->category, ['name', 'long_description']);
      $query->whereHas('product_categories.category', function ($query) {
        $query->where('id', $this->category);
      });
    }

    if ($this->specfilter && !empty($this->selectedKeys)) {
      $query->whereHas('product_specs', function ($query) {
        $query->whereIn('value', $this->selectedKeys);
      });
    }

    switch ($this->orderBy) {
      case 'best_selling':
        $query->orderBy('popularity', 'desc');
        break;
      case 'name_az':
        $query->orderBy('name', $this->orderAsc ? 'asc' : 'desc');
        break;
      case 'name_za':
        $query->orderBy('name', $this->orderAsc ? 'desc' : 'asc');
        break;
      case 'date_old_new':
        $query->orderBy('created_at', $this->orderAsc ? 'asc' : 'desc');
        break;
      case 'date_new_old':
        $query->orderBy('created_at', $this->orderAsc ? 'desc' : 'asc');
      case 'quantity':
        $query->where('quantity', '>', 0)->orderBy('quantity', 'desc');
        break;
      case 'price_az':
        $query->whereHas('product_prices', function ($subquery) {
          $subquery->orderBy('value', 'asc');
        });
        break;
      case 'price_za':
        $query->whereHas('product_prices', function ($subquery) {
          $subquery->orderBy('value', 'desc');
        });
        break;
    }

    return $query->paginate($this->loadAmount);
  }

  public function loadMore()
  {
    $this->loadAmount += 16;
  }
}
