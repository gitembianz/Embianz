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
    public $cart;
    protected $listeners = [
        'showcart' => 'cartshow',
        'newcart' => 'mount',
        'orderprocess' => 'mount',
    ];
    public function render()
    {
        $total = $this->cart ? $this->cart->sum_amount : 0;

        return view('livewire.cart-products-list', [
            'cartItems' => $this->cartItems,
            'total' => $total,
            'currency' => $this->cartItems->isNotEmpty() ? $this->cartItems->first()->product->product_prices->first()->pricelist->currency->name : '',
        ]);
    }
    public function getCartItemsProperty()
    {
        return $this->cart ? Cart_Item::where('cart_id', $this->cart->id)
            ->with([
                'product.media' => function ($query) {
                    $query->where('type', 'min');
                },
                'product.product_prices'
            ])->get() : collect();
    }
    public function mount()
    {
        $this->cart = Cart::where('session_id', app('global_session_id'))
            ->where('status_id', '!=', app('global_cart_closed'))
            ->latest()->first();
    }
    public function cartshow()
    {
        $this->showcart = true;
    }
    public function removeFromCart($productId)
    {
        $product = Product::find($productId);

        if ($this->cart) {
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
