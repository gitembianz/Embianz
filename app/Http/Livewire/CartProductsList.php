<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Cart_Item;
use Illuminate\Support\Facades\DB;


class CartProductsList extends Component
{
    public $showcart = false;
    public $cartId;
    protected $listeners = [
        'showcart' => 'cartshow',
        'orderprocess' => 'orderprocess',
        'newcartlist' => 'getCartItemsProperty',

    ];
    public function render()
    {
        return view('livewire.cart-products-list', [
            'cartItems' => $this->cartItems,
            'currency' => $this->cartItems->isNotEmpty() ? $this->cartItems->first()->product->product_prices->first()->pricelist->currency->name : '',
        ]);
    }
    public function getCartItemsProperty()
    {
        if ($this->cartId && $this->showcart) {
            return Cart_Item::select('id', 'quantity', 'product_id')
                ->where('cart_id', $this->cartId)
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
                            }
                        ]);
                    }
                ])->get();
        } else {
            return collect();
        }
    }
    public function orderprocess()
    {
        $this->mount(null);
    }

    public function mount($cartId)
    {
        $this->cartId = $cartId;
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
            Cart::where('id', $this->cartId)->update([
                'status_id' => app('global_cart_checkout'),
            ]);
            return redirect()->route('order');
        }
    }
}
