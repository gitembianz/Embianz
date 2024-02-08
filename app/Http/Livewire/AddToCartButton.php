<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;
use App\Models\Cart_Item;

class AddToCartButton extends Component
{
    public $product;

    public function mount($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }

    public function addToCart($productId)
    {
        $cart = Cart::where('session_id', app('global_session_id'))->where('status_id', '!=', app('global_cart_closed'))->latest()->first();
        if (!$cart) {
            $baseName = class_basename(Cart::class);
            $cartNumber = 1;
            $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
            while (Cart::where('name', $uniqueName)->exists()) {
                $cartNumber++;
                $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
            }
            $cart = Cart::create([
                'session_id' => app('global_session_id'),
                'name' => $uniqueName,
                'quantity_amount' => 0,
                'sum_amount' => 0,
                'status_id' => app('global_cart_new'),
                'currency_id' => $this->product->product_prices->first()->pricelist->currency_id,
            ]);
            $this->emit('newcart');
        }
        $cartItem = Cart_Item::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        if (!$cartItem) {
            $cartItem = Cart_Item::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price' => $this->product->product_prices->first()->value,
                'quantity' => 1
            ]);
            $cart->increment('quantity_amount');
            $cart->sum_amount += $this->product->product_prices->first()->value;
        } else {
            if ($cartItem->quantity < $this->product->quantity) {

                $cartItem->increment('quantity');
                $cart->increment('quantity_amount');
                $cart->sum_amount += $this->product->product_prices->first()->value;
            }
        }
        $cart->save();
        $this->emit('cartUpdated');
    }
}
