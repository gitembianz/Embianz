<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Cart_Item;
use Illuminate\Support\Facades\DB;


class CartProductsList extends Component
{
    public $showcart = false;
    public $cartId;
    public $voucher = "";
    public $aplicabble_voucher = false;
    public $message = null;
    public $cartmodified = false;

    protected $listeners = [
        'showcart' => 'cartshow',
        'orderprocess' => 'orderprocess',
        'newcartlist' => 'getCartItemsProperty',

    ];
    public function render()
    {
        return view('livewire.cart-products-list', [
            'cart' => $this->cart,
            'cartItems' => $this->cartItems,
            'currency' => $this->cartItems->isNotEmpty() ? $this->cartItems->first()->product->product_prices->first()->pricelist->currency->symbol : '',
        ]);
    }
    public function getCartProperty()
    {
        return Cart::select('id', 'quantity_amount', 'delivery_price', 'seen_by_customer', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value')
            ->where('id', $this->cartId)
            ->with([
                'voucher' => function ($query) {
                    $query->select('code', 'id', 'percent', 'value');
                },
                'carts'
            ])
            ->latest()
            ->first() ?? null;
    }
    public function getCartItemsProperty()
    {
        if ($this->cartId && $this->showcart) {
            return Cart_Item::select('id', 'quantity', 'product_id')
                ->where('cart_id', $this->cartId)
                ->with([
                    'product' => function ($query) {
                        $query->select('id', 'name', 'seo_id', 'active', 'start_date', 'end_date', 'quantity')->with([
                            'media' => function ($query) {
                                $query->select('path', 'name')->where('type', 'min');
                            },
                            'product_prices' => function ($query) {
                                $query->select('product_id', 'value', 'pricelist_id')
                                    ->with(['pricelist' => function ($query) {
                                        $query->select('id', 'currency_id')->with('currency:id,name,symbol');
                                    }]);
                            }
                        ]);
                    }
                ])->get();
        } else {
            return collect();
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
        $this->mount(null);
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

    public function mount($cartId)
    {
        $this->cartId = $cartId;

        if ($this->cart && $this->cart->seen_by_customer) {
            $this->cartmodified = true;
        }
    }

    public function pricechanged()
    {
        foreach ($this->cart->carts as $item) {
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
        }
    }

    public function cartshow()
    {
        $this->showcart = true;
    }
    public function removeFromCart($productId)
    {
        $product = Product::select('id')->with(['product_prices' => function ($query) {
            $query->select('id', 'value', 'product_id');
        }])->findOrFail($productId);

        if ($this->cartId) {
            $cartItem = Cart_Item::where('cart_id', $this->cartId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {

                $cart = Cart::with(['voucher' => function ($query) {
                    $query->select('code', 'id', 'percent', 'value');
                }])->find($this->cartId);

                $amountToSubtract = $product->product_prices->first()->value * $cartItem->quantity;

                if ($cart->voucher && $cart->voucher->percent !== null) {
                    $voucher_value = ($cart->voucher->percent / 100) * ($cart->sum_amount - $amountToSubtract);
                } elseif ($cart->voucher && $cart->voucher->value !== null) {
                    $voucher_value = $cart->voucher->value;
                } else {
                    $voucher_value = 0;
                }
                Cart::where('id', $this->cartId)->update([
                    'quantity_amount' => DB::raw("quantity_amount - $cartItem->quantity"),
                    'sum_amount' => DB::raw("sum_amount - $amountToSubtract"),
                    'final_amount' => DB::raw("CASE WHEN (quantity_amount) = 0 THEN 0 ELSE sum_amount + delivery_price - $voucher_value END"),
                    'voucher_id' => DB::raw("CASE WHEN (quantity_amount) = 0 THEN NULL ELSE voucher_id END"),
                    'voucher_value' => DB::raw("CASE WHEN (quantity_amount) = 0 THEN 0 ELSE $voucher_value  END"),
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
        if ($this->cartItems->isNotEmpty()) {
            foreach ($this->cartItems as $item) {
                if (($item->product->active != true) || ($item->product->start_date > now()->format('Y-m-d')) || ($item->product->end_date < now()->format('Y-m-d'))) {
                    $this->emit('cartUpdated');
                    return;
                }
            }
        }
        $validateQuantity = true;

        if ($this->cartItems->isNotEmpty()) {
            foreach ($this->cartItems as $item) {
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
            Cart::where('id', $this->cartId)->update([
                'status_id' => app('global_cart_checkout'),
            ]);
            return redirect()->route('order');
        }
    }
}
