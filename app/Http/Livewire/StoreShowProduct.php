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
  public $productId;
  public $activeTab = 0;
  public $quantity;
  public $limit = null;
  public $maxlimit = null;
  public $session_id;
  public $wishlist = [];
  protected $listeners = [
    'wishlistUpdated' => 'refreshComponent'
  ];


  public function mount($productId)
  {
    $this->productId = $productId;
    $this->session_id = $_COOKIE['sessionId'];
    $this->quantity = 1;
  }
  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product
    ]);
  }
  public function refreshComponent()
  {
    $this->mount($this->productId);
    $this->render(); // This triggers Livewire to re-render the component
  }
  public function switchTab($index)
  {
    $this->activeTab = $index;
  }
  public function updateCounterValue()
  {
    $this->quantity = $this->quantity;
  }
  public function addToWishlist()
  {
    if (!in_array($this->productId, $this->wishlist)) {
      $this->wishlist[] = $this->productId;
      $this->saveToSession();

      Wishlist::updateOrCreate(
        ['session_id' => $this->session_id, 'product_id' => $this->productId]
      );
      $this->emit('wishlistUpdated');
    }
  }
  public function removeFromWishlist()
  {
    $this->wishlist = array_diff($this->wishlist, [$this->productId]);
    $this->saveToSession();

    Wishlist::where('session_id', $this->session_id)
      ->where('product_id', $this->productId)
      ->delete();
    $this->emit('wishlistUpdated');
  }
  private function saveToSession()
  {
    session([
      'wishlist' => $this->wishlist,
    ]);
  }
  public function toggleWishlist()
  {
    if (in_array($this->productId, $this->wishlist)) {
      $this->removeFromWishlist();
    } else {
      $this->addToWishlist();
    }
  }
  public function incrementCounter()
  {
    $this->limit = $this->product->quantity;
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
  public function getProductProperty()
  {
    return $this->productQuery;
  }
  public function getProductQueryProperty()
  {
    return Product::find($this->productId);
  }
}
