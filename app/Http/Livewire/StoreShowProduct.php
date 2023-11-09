<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Status;
use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;

class StoreShowProduct extends Component
{
  public $product;
  public $productId;
  public $activeTab = 0;
  public $quantity;
  public $limit = null;
  public $maxlimit = null;
  public $mainpath = null;
  public $path = null;
  public $relatedphotos = [];
  public $mainimages;
  public $mainMedia;
  public $session_id;
  public $wishlist = [];

  protected $listeners = ['wishlistUpdated' => 'mount'];

  public function render()
  {
    return view('livewire.store-show-product');
  }
  public function switchTab($index)
  {
    $this->activeTab = $index;
  }
  public function updateCounterValue()
  {
    $this->quantity = $this->quantity;
  }
  public function selectpath($id)
  {
    $this->path = '1';
    $this->mainpath = $id;
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
  public function incrementCounter()
  {
    if ($this->quantity >= $this->limit) {
      $this->maxlimit = true;
      $this->quantity = $this->limit;
    } else {
      $this->quantity++;
    }
  }
  public  function decrementCounter()
  {

    if ($this->quantity > 1) {
      if ($this->quantity == $this->limit) {
        $this->maxlimit = false;
      }
      $this->quantity--;
    }
  }
  public function addToCart($productId)
  {
    $closedStatusId = Status::where('name', 'closed')->where('type', 'cart')->first()->id;
    $product = Product::with('product_prices.pricelist')->find($productId);
    $newStatusId = Status::where('name', 'new')->where('type', 'cart')->first()->id;
    $cart = Cart::where('session_id', $this->session_id)->where('status_id', '!=', $closedStatusId)->latest()->first();
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
        'quantity_amount' => $this->quantity,
        'sum_amount' => ($product->product_prices->first()->value * $this->quantity),
        'status_id' => $newStatusId,
        'currency_id' => $product->product_prices->first()->pricelist->currency_id,
      ]);
    }
    $cartItem = Cart_Item::where('cart_id', $cart->id)->where('product_id', $productId)->first();
    if (!$cartItem) {
      $cartItem = Cart_Item::create([
        'cart_id' => $cart->id,
        'product_id' => $productId,
        'price' => $product->product_prices->first()->value,
        'quantity' => $this->quantity
      ]);
    } else {
      if ($cartItem->quantity < $product->quantity) {

        $cartItem->quantity += $this->quantity;
        $cartItem->save();
        $cart->quantity_amount += $this->quantity;
        $cart->sum_amount += ($product->product_prices->first()->value * $this->quantity);
      } else {
        $this->quantity = $product->quantity;
        $this->maxlimit = true;
      }
    }
    $cart->save();
    $this->quantity = 1;
    $this->emit('cartUpdated');
  }
  public function mount($product)
  {
    $this->product = $product;
    $this->limit = $this->product->quantity;
    $this->session_id = $_COOKIE['sessionId'];
    $this->quantity = 1;

    $this->mainMedia = $this->product->media->firstWhere('location.location', 'main');
    if ($this->mainMedia) {
      if (!$this->path) {
        $this->mainpath = $this->mainMedia->external
          ? $this->mainMedia->path
          : "/{$this->mainMedia->path}{$this->mainMedia->name}";
      }
    }

    $this->mainimages = $this->product->media
      ->where('location.location', '!=', 'search')
      ->sortBy('location_id')
      ->values();

    $this->relatedphotos = $this->mainimages->map(function ($image) {
      return $image->external
        ? $image->path
        : "/{$image->path}{$image->name}";
    });
  }
}
