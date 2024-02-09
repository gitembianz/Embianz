<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Cart_Item;

class StoreCart extends Component

{
  public $cartitems;
  public $wishlist = [];
  public $cart;
  public $delivery;
  public $voucher;
  public $new_price = false;
  public $price;
  public $message;
  public $currency;
  public $session_id;
  public $validatequantity;
  protected $listeners = [
    'cartUpdated' => 'mount',
    'wishlistUpdated' => 'mount'
  ];
  public function mount()
  {
    $this->session_id = app('global_session_id');
    $this->delivery = app('global_delivery_price');
    $this->cart = Cart::where('session_id', app('global_session_id'))->where('status_id', '!=', app('global_cart_closed'))->with('voucher')->first();
    if ($this->cart) {
      $this->currency = $this->cart->currency->name;
      if ($this->cart->voucher_id) {
        $this->new_price = true;
        $this->voucher = $this->cart->voucher->code;
      }
    }
  }
  public function render()
  {
    $data = [
      'cartItems' => $this->cartItems,
      'cart' => $this->cart
    ];
    return view('livewire.store-cart', $data);
  }
  public function getCartItemsProperty()
  {
    if ($this->cart) {
      $cartItems = Cart_Item::where('cart_id', $this->cart->id)->with([
        'product.media' => function ($query) {
          $query->where('type', 'min'); // Filter and limit the media relationship
        },
        'product.product_prices' => function ($query) {
          $query->with('pricelist.currency');
        },
        'product.wishlists'
      ])->get();
      return $cartItems;
    }
    return collect(); // Return an empty collection if no cart items are found
  }
  public function removeFromCart($productId)
  {
    $product = Product::find($productId);
    if ($this->cart->exists) {
      $cart_item = Cart_Item::firstOrNew([
        'cart_id' => $this->cart->id,
        'product_id' => $productId,
      ]);

      if ($cart_item->exists) {
        $this->cart->quantity_amount -= $cart_item->quantity;
        $this->cart->sum_amount -= ($product->product_prices->first()->value * $cart_item->quantity);
        $this->cart->save();
        $cart_item->delete();
        $this->emit('cartUpdated');
      }
    }
  }
  public function increment($productId)
  {
    $product = Product::find($productId);
    $cartItem = Cart_Item::where([
      'cart_id' => $this->cart->id,
      'product_id' => $productId,
    ])->first();

    if ($cartItem->quantity < $product->quantity) {
      $cartItem->increment('quantity');
      $this->cart->quantity_amount += 1;
      $this->cart->sum_amount += $product->product_prices->first()->value;
      $this->cart->save();
      $this->emit('cartUpdated');
    }
  }
  public function test()
  {
    $this->dispatchBrowserEvent('alert__modal');
  }
  public function decrement($productId)
  {
    $product = Product::find($productId);
    $cartItem = Cart_Item::where([
      'cart_id' => $this->cart->id,
      'product_id' => $productId,
    ])->first();

    if ($cartItem && $cartItem->quantity > 1) {
      $cartItem->decrement('quantity');
      $this->cart->quantity_amount -= 1;
      $this->cart->sum_amount -= $product->product_prices->first()->value;
      $this->cart->save();
      $this->emit('cartUpdated');
    } elseif ($cartItem->quantity == 1) {
      return;
    }
  }
  public function addToWishlist($productId)
  {
    if (!in_array($productId, $this->wishlist)) {
      $this->wishlist[] = $productId;
      $this->saveToSession();

      Wishlist::updateOrCreate(
        ['session_id' => app('global_session_id'), 'product_id' => $productId]
      );
      $this->emit('wishlistUpdated');
    }
  }
  public function removeFromWishlist($productId)
  {
    $this->wishlist = array_diff($this->wishlist, [$productId]);
    $this->saveToSession();

    Wishlist::where('session_id', app('global_session_id'))
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
    if ($this->cart->exists) {
      // Search for a voucher with the provided code in the database
      $voucher = Voucher::where('code', $this->voucher)->where('status_id', app('global_voucher_active'))->first();

      if ($voucher) {
        // Voucher found, calculate discount based on percentage
        $discountAmount = $voucher->percent / 100 * $this->cart->sum_amount;
        $this->message = null;
        $this->price = $this->cart->sum_amount;
        $this->cart->voucher_id = $voucher->id;
        $this->cart->final_amount = $this->cart->sum_amount - $discountAmount + $this->delivery;
        $this->cart->save();
        $this->new_price = true;
      } else {
        $this->message = "Voucher not found!";
      }
    }
  }
  public function continue()
  {
    $cartitems = Cart_Item::where('cart_id', $this->cart->id)->get();
    if ($cartitems) {
      foreach ($cartitems as $item) {
        if ($item->quantity > $item->product->quantity) {
          $this->validatequantity = false;
          $this->dispatchBrowserEvent('alert__modal');
          return;
        } else {
          $this->validatequantity = true;
        }
      }
    }
    if ($this->validatequantity) {
      if ($this->new_price) {
        $this->cart->final_amount;
      } else {
        $this->cart->final_amount = $this->cart->sum_amount + $this->delivery;
      }
      $this->cart->status_id = app('global_cart_checkout');
      $this->cart->delivery_price = $this->delivery;
      $this->cart->save();
      return redirect()->route('order');
    }
  }
}
