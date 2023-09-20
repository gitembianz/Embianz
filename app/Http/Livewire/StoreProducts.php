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
  public $specfilter = false;
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
  }
  public function render()
  {
    // Use $this->getProducts() to fetch products
    $this->products = $this->getProducts();

    return view('livewire.store-products');
  }

  public function getProducts()
  {

    $query = Product::name($this->search);
    // if ($this->specfilter) {

    //   if (!empty($this->selectedSpecValues)) {
    //     // Loop through each selected spec value and add a whereHas clause for each
    //     foreach ($this->selectedSpecValues as $outerKey => $outerValue) {
    //       foreach ($outerValue as $innerKey => $innerValue) {
    //         foreach ($innerValue as $valueKey => $value) {
    //           dd($value);
    //           if ($valueKey === 'value') {
    //             $query->orWhereHas('product_specs', function ($q) use ($value) {
    //               $q->where('value', $value);
    //             });
    //           }
    //         }
    //       }
    //     }
    //   }
    // }

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
  // public function applyFilter()
  // {
  //   $this->specfilter = true;
  // }

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
