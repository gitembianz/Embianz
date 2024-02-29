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
  public $message = null;
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
    return Cart::select('id', 'quantity_amount', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->with(['voucher' => function ($query) {
        $query->select('code', 'id', 'percent', 'value');
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

  public function removeFromCart($productId)
  {
    $product = Product::select('id')->with(['product_prices' => function ($query) {
      $query->select('id', 'value', 'product_id');
    }])->findOrFail($productId);

    if ($this->cart->id) {
      $cartItem = Cart_Item::where('cart_id', $this->cart->id)
        ->where('product_id', $productId)
        ->first();

      if ($cartItem) {
        $amountToSubtract = $product->product_prices->first()->value * $cartItem->quantity;
        if ($this->cart->voucher && $this->cart->voucher->percent !== null) {
          $voucher_value = ($this->cart->voucher->percent / 100) * ($this->cart->sum_amount - $amountToSubtract);
        } elseif ($this->cart->voucher && $this->cart->voucher->value !== null) {
          $voucher_value = $this->cart->voucher->value;
        } else {
          $voucher_value = 0;
        }
        Cart::where('id', $this->cart->id)->update([
          'quantity_amount' => DB::raw("quantity_amount - $cartItem->quantity"),
          'sum_amount' => DB::raw("sum_amount - $amountToSubtract"),
          'final_amount' => DB::raw("CASE WHEN (quantity_amount) = 0 THEN 0 ELSE sum_amount + delivery_price - $voucher_value END"),
          'voucher_id' => DB::raw("CASE WHEN (quantity_amount) = 0 THEN NULL ELSE voucher_id END"),
          'voucher_value' => DB::raw("CASE WHEN (quantity_amount) = 0 THEN 0 ELSE $voucher_value END"),
          'updated_at' => now(),
          'status_id' => app('global_cart_new')
        ]);
        $cartItem->delete();
        $this->emit('cartUpdated');
      }
    }
  }

  public function increment($id)
  {
    if ($this->cart && $this->cartItems->isNotEmpty()) {
      $cartitem_to_increment = $this->cartItems->where('id', $id)->first();
      if ($cartitem_to_increment->quantity < $cartitem_to_increment->product->first()->quantity) {
        $cartitem_to_increment->increment('quantity');
        $this->cart->increment('quantity_amount');
        $this->cart->delivery_price = app('global_delivery_price');
        $this->cart->sum_amount += $cartitem_to_increment->product->product_prices->first()->value;
        if ($this->cart->voucher && $this->cart->voucher->percent !== null) {
          $this->cart->voucher_value = ($this->cart->voucher->percent / 100) * $this->cart->sum_amount;
        }
        $this->cart->final_amount = $this->cart->sum_amount + app('global_delivery_price');
        $this->cart->final_amount -= $this->cart->voucher_value;
        $this->cart->status_id = app('global_cart_new');
        $this->cart->save();
        $this->emit('cartUpdated');
      }
    } else {
      $this->emit('newcart');
      return;
    }
  }

  public function decrement($id)
  {
    if ($this->cart && $this->cartItems->isNotEmpty()) {
      $cartitem_to_decrement = $this->cartItems->where('id', $id)->first();
      if ($cartitem_to_decrement && $cartitem_to_decrement->quantity > 1) {
        $cartitem_to_decrement->decrement('quantity');
        $this->cart->decrement('quantity_amount');
        $this->cart->delivery_price = app('global_delivery_price');
        $this->cart->sum_amount -= $cartitem_to_decrement->product->product_prices->first()->value;
        if ($this->cart->voucher && $this->cart->voucher->percent !== null) {
          $this->cart->voucher_value = ($this->cart->voucher->percent / 100) * $this->cart->sum_amount;
        }
        $this->cart->final_amount = $this->cart->sum_amount + app('global_delivery_price');
        $this->cart->final_amount -= $this->cart->voucher_value;
        $this->cart->status_id = app('global_cart_new');
        $this->cart->save();
        $this->emit('cartUpdated');
      } else {
        return;
      }
    } else {
      $this->emit('newcart');
      return;
    }
  }

  public function checkvoucher()
  {
    if ($this->cart) {
      $voucher = Voucher::where('code', $this->voucher)
        ->where('status_id', app('global_voucher_active'))
        ->where('start_date', '<',  now())
        ->where('end_date', '>',  now())
        ->first();
      if ($voucher) {
        if ($voucher && $voucher->percent !== null) {
          $discountAmount = ($voucher->percent / 100) * $this->cart->sum_amount;

          $this->cart->update([
            'final_amount' => ($this->cart->sum_amount + app('global_delivery_price') - $discountAmount),
            'voucher_id' => $voucher->id,
            'voucher_value' => $discountAmount,
            'updated_at' => now(),
          ]);
        } else {
          if ($voucher->value > $this->cart->sum_amount) {
            $this->message = null;
            $this->cart->update([
              'final_amount' =>  app('global_delivery_price'),
              'voucher_id' => $voucher->id,
              'voucher_value' => $voucher->value,
              'updated_at' => now(),
            ]);
          } else {
            $this->message = null;
            $this->cart->update([
              'final_amount' => ($this->cart->sum_amount + app('global_delivery_price') - $voucher->value),
              'voucher_id' => $voucher->id,
              'voucher_value' => $voucher->value,
              'updated_at' => now(),
            ]);
          }
        }
      } else {
        $this->message = "Voucher-ul nu a fost gasit!";
        $this->voucher = "";
      }
    } else {
      $this->message = null;
      $this->emit('newcart');
      return;
    }
  }

  public function removevoucher()
  {
    $this->cart->update([
      'final_amount' => ($this->cart->sum_amount + app('global_delivery_price')),
      'voucher_id' => null,
      'voucher_value' => 0,
      'updated_at' => now(),
      'status_id' => app('global_cart_new')
    ]);
    $this->message = null;
    $this->voucher = "";
  }

  public function continue()
  {
    $validateQuantity = true;

    if ($this->cartItems->isNotEmpty()) {
      foreach ($this->cartItems as $item) {
        if ($item->quantity < $item->product->quantity) {
          $validateQuantity = false;
          $this->dispatchBrowserEvent('alert__modal');
          return;
        }
      }
    }

    if ($validateQuantity) {
      Cart::where('id', $this->cart->id)->update([
        'status_id' => app('global_cart_checkout'),
      ]);
      return redirect()->route('order');
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
}
