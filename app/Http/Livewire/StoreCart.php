<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Cart_Item;
use Illuminate\Support\Facades\DB;


class StoreCart extends Component

{
  public $delivery;
  public $voucher;
  public $message = null;
  public $session_id;
  public $aplicabble_voucher = false;
  public $wishlistItems;


  protected $listeners = [
    'cartUpdated' => 'mount',
  ];

  public function mount()
  {
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();

    $this->wishlistItems = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
  }
  public function isInWishlist($productId)
  {
    return in_array($productId, $this->wishlistItems);
  }
  public function getCartProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      $cachedProducts = app()->make('cached_products')->keyBy('id');

      $cart = Cart::select('id', 'quantity_amount', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value')
        ->where('session_id', $this->session_id)
        ->where('status_id', '!=', app('global_cart_closed'))
        ->with([
          'voucher' => function ($query) {
            $query->select('code', 'id', 'percent', 'value');
          },
          'cartItems' => function ($query) {
            $query->select('id', 'cart_id', 'product_id', 'price', 'quantity');
          }
        ])
        ->latest()
        ->first();

      if ($cart) {
        foreach ($cart->cartItems as $item) {
          if ($cachedProducts->has($item->product_id)) {
            $item->setRelation('product', $cachedProducts->get($item->product_id));
          }
        }
      }

      return $cart;
    } else {
      return Cart::select('id', 'quantity_amount', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value')
        ->where('session_id', $this->session_id)
        ->where('status_id', '!=', app('global_cart_closed'))
        ->with([
          'voucher' => function ($query) {
            $query->select('code', 'id', 'percent', 'value');
          },
          'cartItems' => function ($query) {
            $query->select('id', 'cart_id', 'product_id', 'price', 'quantity')
              ->with([
                'product' => function ($query) {
                  $query->select('id', 'name', 'seo_id', 'active', 'start_date', 'end_date', 'quantity')
                    ->with([
                      'media' => function ($query) {
                        $query->select('path', 'name', 'type')->where('type', 'min');
                      },
                      'product_prices' => function ($query) {
                        $query->select('product_id', 'value');
                      },
                    ]);
                }
              ]);
          }
        ])
        ->latest()
        ->first() ?? null;
    }
  }


  public function removeFromCart($productId)
  {

    if ($this->cart->id) {
      $cartItem = Cart_Item::where('cart_id', $this->cart->id)
        ->where('product_id', $productId)
        ->first();

      if ($cartItem) {
        $amountToSubtract = $cartItem->price * $cartItem->quantity;
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
    if ($this->cart) {
      $cartitem_to_increment = $this->cart->cartItems()->where('id', $id)->first();
      if ($cartitem_to_increment->quantity < $cartitem_to_increment->product->quantity || (app()->has('global_preorder') && app('global_preorder') === 'true')) {
        $cartitem_to_increment->increment('quantity');
        $this->cart->increment('quantity_amount');
        $this->cart->delivery_price = app('global_delivery_price');
        if ($cartitem_to_increment->price != $cartitem_to_increment->product->product_prices->first()->value) {
          $cartitem_to_increment->price = $cartitem_to_increment->product->product_prices->first()->value;
          $cartitem_to_increment->save();
          $sum_amount = 0;
          foreach ($this->cart->carts as $item) {
            $sum_amount = $sum_amount + $item->price * $item->quantity;
          }
          $this->cart->sum_amount = $sum_amount;
          $this->cart->seen_by_customer = true;
        } else {
          $this->cart->sum_amount += $cartitem_to_increment->product->product_prices->first()->value;
        }
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
    if ($this->cart) {
      $cartitem_to_decrement = $this->cart->cartItems->where('id', $id)->first();
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
    $this->emit('cartUpdated');
  }
  public function checkvoucher()
  {
    if ($this->cart) {
      $voucher = Voucher::where('code', $this->voucher)
        ->where('status_id', app('global_voucher_active'))
        ->where('start_date', '<=',  now()->format('Y-m-d'))
        ->where('end_date', '>=',  now()->format('Y-m-d'))
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
        $this->message = "Voucher-ul '" . $this->voucher .  "' nu a fost gasit!";
        $this->voucher = "";
        return false;
      }
      $this->emit('cartUpdated');
      $this->voucher = "";
      return true;
    } else {
      $this->message = null;
      $this->emit('newcart');
      return;
    }
  }

  public function cancel_aplicabble()
  {
    $this->voucher = "";
    $this->continue();
  }
  public function confirm_aplicabble()
  {
    if ($this->checkvoucher()) {
      $this->continue();
    } else {
      $this->aplicabble_voucher = false;
      return;
    }
  }

  public function continue()
  {

    if ($this->voucher != "") {
      $this->aplicabble_voucher = true;
      return;
    }
    if ($this->cart->quantity_amount != 0) {
      foreach ($this->cart->cartItems as $item) {
        if (($item->product->active != true) || ($item->product->start_date > now()->format('Y-m-d')) || ($item->product->end_date < now()->format('Y-m-d'))) {
          $this->emit('cartUpdated');
          return;
        }
      }
    }

    $validateQuantity = true;

    if ($this->cart->quantity_amount != 0) {
      foreach ($this->cart->cartItems as $item) {
        if ($item->quantity > $item->product->quantity && (app()->has('global_preorder') && app('global_preorder') != 'true')) {
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
      'cart' => $this->cart
    ];
    return view('livewire.store-cart', $data);
  }
}
