<?php

namespace App\Http\Livewire;

use App\Models\Product;
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

  public function loadMore()
  {
    $this->loadAmount += 10;
  }

  public function render()
  {
    return view('livewire.store-products', [
      'products' => $this->products
    ]);
  }
  public function getProductsProperty()
  {
    return $this->productsQuery->limit($this->loadAmount)->get();
  }
  public function getProductsQueryProperty()
  {
    return Product::name($this->search)->orderBy('created_at', 'desc');
  }


  public function addToWishlist($productId)
  {
    if (!in_array($productId, $this->wishlist)) {
      $this->wishlist[] = $productId;
      $this->saveToSession();
      $session_id = Session::getId();

      Wishlist::updateOrCreate(
        ['session_id' => $session_id, 'product_id' => $productId]
      );
      $this->emit('wishlistUpdated');
    }
  }

  public function removeFromWishlist($productId)
  {
    $this->wishlist = array_diff($this->wishlist, [$productId]);
    $this->saveToSession();
    $session_id = Session::getId();

    Wishlist::where('session_id', $session_id)
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
