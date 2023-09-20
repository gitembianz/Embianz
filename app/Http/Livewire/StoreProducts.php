<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Specs;
use App\Models\Wishlist;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount = 9;
  public $search = "";
  public $quantity = 20;
  public $wishlist = [];
  public $session_id;
  public $specification;
  public $property = false;
  public $selectedSpecValues = [];
  public $orderBy = 'best_selling'; // Default sorting order
  public $orderAsc = true;
  public $products;

  protected $listeners = ['wishlistUpdated' => 'mount'];

  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function mount()
  {
    $this->session_id = Session::getId();
    $this->specification = Specs::all();
    $this->products = $this->getProducts();
  }
  public function render()
  {
    // Use $this->getProducts() to fetch products

    return view('livewire.store-products');
  }

  public function getProducts()
  {
    $query = Product::name($this->search);

    switch ($this->orderBy) {
      case 'best_selling':
        $query->orderBy('popularity', $this->orderAsc ? 'asc' : 'desc');
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
    }

    return $query->limit($this->loadAmount)->get();
  }

  public function applyFilter()
  {
    // The filtering logic is handled here after clicking the "Apply" button
    $query = Product::name($this->search);

    if (!empty($this->selectedSpecValues)) {
      // Flatten the selectedSpecValues array and get unique spec_ids
      $specIds = $this->flattenAndUnique($this->selectedSpecValues);

      // Use whereHas to filter products based on selected spec values
      $query->whereHas('product_specs', function ($q) use ($specIds) {
        $q->whereIn('spec_id', $specIds);
      });
    }

    switch ($this->orderBy) {
      case 'best_selling':
        $query->orderBy('popularity', $this->orderAsc ? 'asc' : 'desc');
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
    }

    $this->products = $query->limit($this->loadAmount)->get();
  }

  // Helper method to flatten and get unique values
  private function flattenAndUnique($array)
  {
    $result = [];
    array_walk_recursive($array, function ($value) use (&$result) {
      $result[] = $value;
    });
    return array_unique($result);
  }





  public function addToWishlist($productId)
  {
    if (!in_array($productId, $this->wishlist)) {
      $this->wishlist[] = $productId;
      $this->saveToSession();

      Wishlist::updateOrCreate(
        ['session_id' => $this->session_id, 'product_id' => $productId]
      );
      $this->emit('wishlistUpdated');
    }
  }

  public function removeFromWishlist($productId)
  {
    $this->wishlist = array_diff($this->wishlist, [$productId]);
    $this->saveToSession();

    Wishlist::where('session_id', $this->session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('wishlistUpdated');
  }

  private function saveToSession()
  {
    session(['wishlist' => $this->wishlist]);
  }
  public function toggleWishlist($productId)
  {
    if (in_array($productId, $this->wishlist)) {
      $this->removeFromWishlist($productId);
    } else {
      $this->addToWishlist($productId);
    }
  }
}
