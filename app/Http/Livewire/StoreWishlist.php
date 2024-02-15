<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Cart;
use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Cart_Item;


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
    return Product::whereIn('id', $wishlist)->select('id', 'name', 'seo_id', 'short_description')
      ->where('active', true)
      ->with([
        'media' => function ($query) {
          $query->select('path', 'name')->where('type', 'min');
        },
        'product_prices' => function ($query) {
          $query->select('product_id', 'value', 'pricelist_id')
            ->with(['pricelist' => function ($query) {
              $query->select('id', 'currency_id')->with('currency:id,name');
            }]);
        }
      ])->get();
  }

  public function addToCart($productId)
  {
    $cart = Cart::where('session_id', $this->session_id)->where('status_id', '!=', app('global_cart_closed'))->latest()->first();
    $product = Product::with(['product_prices' => function ($query) {
      $query->select('product_id', 'value', 'pricelist_id')
        ->with(['pricelist' => function ($query) {
          $query->select('id', 'currency_id')->with('currency:id,name');
        }]);
    }])->find($productId);
    if (!$cart) {
      $baseName = class_basename(Cart::class);
      $cartNumber = 1;
      $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
      while (Cart::where('name', $uniqueName)->exists()) {
        $cartNumber++;
        $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
      }
      $cart = Cart::create([
        'session_id' => $this->session_id,
        'name' => $uniqueName,
        'quantity_amount' => 0,
        'sum_amount' => 0,
        'status_id' => app('global_cart_new'),
        'currency_id' => $product->product_prices->first()->pricelist->currency_id,
      ]);
      $this->emit('newcart');
    }
    $cartItem = Cart_Item::where('cart_id', $cart->id)->where('product_id', $productId)->first();
    if (!$cartItem) {
      $cartItem = Cart_Item::create([
        'cart_id' => $cart->id,
        'product_id' => $productId,
        'price' => $product->product_prices->first()->value,
        'quantity' => 1
      ]);
      $cart->increment('quantity_amount');
      $cart->sum_amount += $product->product_prices->first()->value;
    } else {
      if ($cartItem->quantity < $product->quantity) {

        $cartItem->increment('quantity');
        $cart->increment('quantity_amount');
        $cart->sum_amount += $product->product_prices->first()->value;
      }
    }
    $cart->save();
    $this->emit('cartUpdated');
  }
}