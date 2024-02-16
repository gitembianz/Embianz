<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Cart_Item;
use Illuminate\Support\Facades\DB;


class StoreCart extends Component

{
  public $delivery;
  public $voucher;
  public $new_price = false;
  public $message;
  public $session_id;

  protected $listeners = [
    'cartUpdated' => 'mount',
  ];

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

  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount', 'sum_amount', 'voucher_id')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->with(['voucher' => function ($query) {
        $query->select('code');
      }])
      ->latest()
      ->first() ?? null;
  }

  public function getCartItemsProperty()
  {
    if ($this->cart) {
      return Cart_Item::select('id', 'quantity', 'price', 'product_id')
        ->where('cart_id', $this->cart->id)
        ->with([
          'product' => function ($query) {
            $query->select('id', 'name', 'seo_id')->with([
              'media' => function ($query) {
                $query->select('path', 'name')->where('type', 'min');
              },
              'product_prices' => function ($query) {
                $query->select('product_id', 'value', 'pricelist_id')
                  ->with(['pricelist' => function ($query) {
                    $query->select('id', 'currency_id')->with('currency:id,name');
                  }]);
              },
              'wishlists' => function ($query) {
                $query->select('id', 'product_id')->where('session_id', $this->session_id);
              }
            ]);
          }
        ])->get() ?? collect();
    } else {
      return collect();
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

  public function removevoucher()
  {
    $this->cart->voucher_id = null;
    $this->new_price = false;
    $this->voucher = "";
    $this->cart->save();
  }

  public function removeFromCart($productId)
  {
    $product = Product::select('id')->with(['product_prices' => function ($query) {
      $query->select('id', 'value', 'product_id');
    }])->findOrFail($productId);

    if ($this->cart) {
      $cartItem = Cart_Item::where('cart_id', $this->cart->id)
        ->where('product_id', $productId)
        ->first();

      if ($cartItem) {
        $amountToSubtract = $product->product_prices->first()->value * $cartItem->quantity;
        Cart::where('id', $this->cart->id)->update([
          'quantity_amount' => DB::raw("quantity_amount - $cartItem->quantity"),
          'sum_amount' => DB::raw("sum_amount - $amountToSubtract"),
          'final_amount' => DB::raw("CASE WHEN (sum_amount - $amountToSubtract) = 0 THEN 0 ELSE final_amount - $amountToSubtract END"),
          'voucher_id' => DB::raw("CASE WHEN (sum_amount - $amountToSubtract) = 0 THEN NULL END"),
        ]);

        $cartItem->delete();
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
  public function checkvoucher()
  {
    if ($this->cart->exists) {
      // Search for a voucher with the provided code in the database
      $voucher = Voucher::where('code', $this->voucher)->where('status_id', app('global_voucher_active'))->first();

      if ($voucher) {
        // Voucher found, calculate discount based on percentage
        $discountAmount = $voucher->percent / 100 * $this->cart->sum_amount;
        $this->message = null;
        $this->cart->voucher_id = $voucher->id;
        $this->cart->final_amount = $this->cart->sum_amount - $discountAmount + app('global_delivery_price');
        $this->cart->save();
        $this->new_price = true;
      } else {
        $this->message = "Voucher not found!";
      }
    }
  }
  public function continue()
  {
    $validateQuantity = true;
    $cartitems = Cart_Item::where('cart_id', $this->cart->id)->get();
    if ($cartitems) {
      foreach ($cartitems as $item) {
        if ($item->quantity > $item->product->quantity) {
          $validateQuantity = false;
          $this->dispatchBrowserEvent('alert__modal');
          return;
        } else {
          $validateQuantity = true;
        }
      }
    }
    if ($validateQuantity) {
      if ($this->new_price) {
        $this->cart->final_amount;
      } else {
        $this->cart->final_amount = $this->cart->sum_amount + app('global_delivery_price');
      }
      $this->cart->status_id = app('global_cart_checkout');
      $this->cart->delivery_price = app('global_delivery_price');
      $this->cart->save();
      return redirect()->route('order');
    }
  }
}
