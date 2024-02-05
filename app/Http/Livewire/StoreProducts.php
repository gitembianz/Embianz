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
    $allKeys = array_keys(array_merge(...array_values($filteredValues)));
    // dd($this->selectedSpecValues);
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
    $this->selectedKeys = $allKeys;
  }
  public function clearall()
  {
    $this->selectedSpecValues = [];
    $this->selectedSpecNames = [];
    $this->selectedKeys = [];
    $this->specfilter = false;
  }

  public function getProductsProperty()
  {
    $query = Product::name($this->search)->where('active', true)->with([
      'product_prices',
      'product_prices.pricelist.currency',
      'media' => function ($query) {
        $query->where('type', 'main');
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

    return $query->paginate($this->loadAmount);
  }

  public function loadMore()
  {
    $this->loadAmount += 16;
  }
}
