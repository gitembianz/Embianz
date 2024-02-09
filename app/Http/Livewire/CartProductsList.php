<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\Store_Settings;

class CartProductsList extends Component
{
    public $showcart = false;
    public $cartId;
    public $total;
    protected $listeners = [
        'showcart' => 'cartshow',
        'orderprocess' => 'mount',
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
        if ($this->cartId) {
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
    public function mount($cartId, $total)
    {
        $this->cartId = $cartId;
        $this->total = $total;
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
        $delivery = Store_Settings::where('parameter', 'delivery_price')->first()->value;
        $validatequantity = true;
        $cartitems = Cart_Item::where('cart_id', $this->cart->id)->get();

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
            $this->cart->final_amount = $this->cart->sum_amount + $delivery;
            $this->cart->status_id = app('global_cart_checkout');
            $this->cart->delivery_price = $delivery;
            $this->cart->save();
            return redirect()->route('order');
        }
    }
}
