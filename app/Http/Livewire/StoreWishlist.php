<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;

class StoreWishlist extends Component
{
  protected $listeners = ['wishlistUpdated' => 'mount'];
  public $session_id;


  public function render()
  {
    $data = [
      'wishlistitems' => $this->wishlistItems
    ];
    return view('livewire.store-wishlist', $data);
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
    $this->session_id = $this->getSessionId();
  }
  public function removeFromWishlist($productId)
  {
    Wishlist::where('session_id', $this->session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('wishlistUpdated');
  }
  public function getWishlistItemsProperty()
  {
    $wishlist = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
    return Product::whereIn('id', $wishlist)->with([
      'media' => function ($query) {
        $query->where('type', 'min');
      }
    ])->get();
  }
}
