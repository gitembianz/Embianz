<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Cart_Item;
use Illuminate\Support\Facades\Session;

class StoreCart extends Component

{
  public $cartitems;
  public $wishlist = [];
  public $session_id;
  public $deliverry;
  public $voucher;
  public $new_price = false;
  public $price;
  public $message;
  protected $listeners = [
    'cartUpdated' => 'mount',
    'wishlistUpdated' => 'mount'
  ];
  public function render()
  {
    $data = [
      'cartItems' => $this->cartItems,
      'cart' => Cart::where('session_id', $this->session_id)->first()
    ];
    return view('livewire.store-cart', $data);
  }
  public function getCartItemsProperty()
  {
    $cart = Cart::where('session_id', $this->session_id)->where('status', '!=', 'closed')->first();

    if ($cart !== null) {
      $cartItems = Cart_Item::where('cart_id', $cart->id)->with('product')->get();

      return $cartItems;
    }

    return collect(); // Return an empty collection if no cart items are found
  }
  public function removeFromCart($productId)
  {
    $session_id = Session::getId();
    $cart = Cart::where('session_id', $session_id)->first();
    $product = Product::find($productId);

    if ($cart !== null) {
      $cart_item = Cart_Item::firstOrNew([
        'cart_id' => $cart->id,
        'product_id' => $productId,
      ]);

      if ($cart_item->exists) {
        $cart->quantity_amount -= $cart_item->quantity;
        $cart->sum_amount -= ($product->product_prices->first()->value * $cart_item->quantity);
        $cart->save();
        $cart_item->delete();
        $this->emit('cartUpdated');
      }
    }
  }
  public function mount()
  {
    // Initial load of cartitems
    $this->session_id = Session::getId();
    $this->cartitems = $this->getCartItemsProperty();
    $this->deliverry = 20;
  }
  public function increment($productId)
  {
    $cart = Cart::where('session_id', $this->session_id)->where('status', '!=', 'closed')->first();
    $product = Product::find($productId);
    $cartItem = Cart_Item::where([
      'cart_id' => $cart->id,
      'product_id' => $productId,
    ])->first();

    if ($cartItem) {
      $cartItem->increment('quantity');
      if ($cartItem->exists) {
        $cart->quantity_amount += 1;
        $cart->sum_amount += $product->product_prices->first()->value;
        $cart->save();
        $this->emit('cartUpdated');
      }
    }
  }
  public function decrement($productId)
  {
    $cart = Cart::where('session_id', $this->session_id)->where('status', '!=', 'closed')->first();
    $product = Product::find($productId);
    $cartItem = Cart_Item::where([
      'cart_id' => $cart->id,
      'product_id' => $productId,
    ])->first();

    if ($cartItem && $cartItem->quantity > 1) {
      $cartItem->decrement('quantity');
      $cart->quantity_amount -= 1;
      $cart->sum_amount -= $product->product_prices->first()->value;
      $cart->save();
      $this->emit('cartUpdated');
    } elseif ($cartItem->quantity == 1) {
      $cartItem->delete();
      $cart->quantity_amount -= 1;
      $cart->sum_amount -= $product->product_prices->first()->value;
      $cart->save();
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
  public function checkvoucher()
  {
    // Retrieve the cart
    $cart = Cart::where('session_id', $this->session_id)->first();

    if ($cart) {
      // Search for a voucher with the provided code in the database
      $voucher = Voucher::where('code', $this->voucher)->first();

      if ($voucher) {
        // Voucher found, calculate discount based on percentage
        $discountAmount = $voucher->percent / 100 * $cart->sum_amount;
        $this->message = null;
        $this->price = $cart->sum_amount;
        $cart->sum_amount -= $discountAmount;
        $cart->save();
        $this->new_price = true;
      } else {
        $this->message = "Voucher not found!";
      }
    }
  }
  public function continue()
  {
    $cart = Cart::where('session_id', $this->session_id)->where('status', '!=', 'closed')->first();
    if ($this->new_price) {
      $cart->final_amount = $this->price;
      $cart->status = "order";
      $cart->save();
    } else {
      $cart->final_amount = $cart->sum_amount + $this->deliverry;
      $cart->status = "order";
      $cart->save();
    }
    return redirect()->route('order');
  }
}
