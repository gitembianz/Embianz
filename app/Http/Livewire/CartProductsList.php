<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Cart_Item;
use Illuminate\Support\Facades\DB;


class CartProductsList extends Component
{
    public $showcart = false;
    public $voucher = "";
    public $aplicabble_voucher = false;
    public $message = null;
    public $cartmodified = false;
    public $session_id;

    protected $listeners = [
        'showcart' => 'cartshow',
        'orderprocess' => 'orderprocess',
        'newcartlist' => 'getCartItemsProperty',
        'cartUpdated' => 'mount',
    ];
    public function render()
    {
        if ($this->showcart) {

            return view('livewire.cart-products-list', [
                'cart' => $this->cart,
            ]);
        } else {
            return view('livewire.cart-products-list');
        }
    }
    public function getCartProperty()
    {
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
                                            $query->select('path', 'name')->where('type', 'min');
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

    private function getSessionId()
    {
        if (array_key_exists('sessionId', $_COOKIE)) {
            return $_COOKIE['sessionId'];
        } else {
            return session()->getId();
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
    public function orderprocess()
    {
        $this->mount();
    }

    public function updatingShowcart()
    {
        $this->message = null;
        $this->voucher = "";
    }

    public function seen()
    {
        $this->cart->seen_by_customer = false;
        $this->cart->save();
        $this->cartmodified = false;

        return;
    }

    public function mount()
    {
        $this->session_id = $this->getSessionId();
    }

    public function pricechanged()
    {
        foreach ($this->cart->cartItems as $item) {
            if (optional($item->product->product_prices->first())->value) {

                if ($item->price != $item->product->product_prices->first()->value) {
                    $item->price = $item->product->product_prices->first()->value;
                    $item->save();
                    $sum_amount = 0;
                    foreach ($this->cart->carts as $element) {
                        $sum_amount = $sum_amount + $element->price * $element->quantity;
                    }
                    $this->cart->sum_amount = $sum_amount;
                    if ($this->cart->voucher && $this->cart->voucher->percent !== null) {
                        $this->cart->voucher_value = ($this->cart->voucher->percent / 100) * $this->cart->sum_amount;
                    } elseif ($this->cart->voucher && $this->cart->voucher->value !== null) {
                        $this->cart->voucher_value = $this->cart->voucher->value;
                    }
                    $this->cart->final_amount = $this->cart->sum_amount + app('global_delivery_price');
                    $this->cart->final_amount -= $this->cart->voucher_value;
                    $this->cart->seen_by_customer = true;
                    $this->cart->save();
                    $this->cartmodified = true;
                    return;
                }
            } else {
                continue;
            }
        }
    }

    public function cartshow()
    {
        $this->showcart = true;
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
                if ($item->quantity > $item->product->quantity) {
                    $validateQuantity = false;
                    if (app()->has('global_order_error_quantity')) {
                        $message = app('global_order_error_quantity');
                    } else {
                        $message = "Vă rog verificați detaliile comenzii!";
                    }
                    $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
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
}