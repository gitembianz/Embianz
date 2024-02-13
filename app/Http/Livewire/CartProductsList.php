<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Cart_Item;

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
            return  Cart_Item::where('cart_id', $this->cartId)
                ->with([
                    'product.media' => function ($query) {
                        $query->where('type', 'min');
                    },
                    'product.product_prices'
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
        $product = Product::find($productId);

        if ($this->cartId) {
            $cart_item = Cart_Item::firstOrNew([
                'cart_id' => $this->cartId,
                'product_id' => $productId,
            ]);

            if ($cart_item->exists) {
                $cart = Cart::find($this->cartId);
                $cart->quantity_amount -= $cart_item->quantity;
                $cart->sum_amount -= ($product->product_prices->first()->value * $cart_item->quantity);
                $cart->save();
                $cart_item->delete();
                $this->emit('cartUpdated');
            }
        }
    }
    public function continue()
    {
        $validatequantity = true;
        $cartitems = Cart_Item::where('cart_id', $this->cartId)->get();

        if ($cartitems->isNotEmpty()) {
            foreach ($cartitems as $item) {
                if ($item->quantity > $item->product->quantity) {
                    $validatequantity = false;
                    $this->dispatchBrowserEvent('alert__modal');
                    return;
                }
            }
        }

        if ($validatequantity) {
            $cart = Cart::find($this->cartId);
            $cart->final_amount = $cart->sum_amount + app('global_delivery_price');
            $cart->status_id = app('global_cart_checkout');
            $cart->delivery_price = app('global_delivery_price');
            $cart->save();
            return redirect()->route('order');
        }
    }
}
