<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;

class StoreCart extends Component

{
  public $cartitems;
  public $wishlist = [];
  public $session_id;
  protected $listeners = [
    'cartUpdated' => 'mount',
    'wishlistUpdated' => 'mount'
  ];

  public function render()
  {
    $data = [
      'cartitems' => $this->cartItems
    ];
    return view('livewire.store-cart', $data);
  }
  public function getCartItemsProperty()
  {
    $session_id = Session::getId();
    $cart = Cart::where('session_id', $session_id)->pluck('product_id')->toArray();
    return Product::whereIn('id', $cart)->get();
  }
  public function removeFromCart($productId)
  {
    $session_id = Session::getId();
    Cart::where('session_id', $session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('cartUpdated');
  }
  public function mount()
  {
    // Initial load of cartitems
    $this->session_id = Session::getId();
    $this->cartitems = $this->getCartItemsProperty();
  }
  public function increment($productId)
  {
    $cartItem = Cart::where([
      'session_id' => Session::getId(),
      'product_id' => $productId,
    ])->first();

    if ($cartItem) {
      $cartItem->increment('quantity');
      $this->emit('cartUpdated');
    }
  }
  public function decrement($productId)
  {
    $cartItem = Cart::where([
      'session_id' => Session::getId(),
      'product_id' => $productId,
    ])->first();

    if ($cartItem && $cartItem->quantity > 1) {
      $cartItem->decrement('quantity');
      $this->emit('cartUpdated');
    }
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
    session([
      'wishlist' => $this->wishlist
    ]);
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
