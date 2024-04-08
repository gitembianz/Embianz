<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;
use App\Models\Cart_Item;

class AddToCartButton extends Component
{
    public $product;
    public $session_id;

    public function mount($product)
    {
        $this->product = $product;
        $this->session_id = $this->getSessionId();
    }
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

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }

    public function addToCart($productId)
    {
        $cart = Cart::where('session_id', $this->session_id)
            ->where('status_id', '!=', app('global_cart_closed'))
            ->with('voucher')
            ->latest()
            ->first();

        if (!$cart) {
            $baseName = class_basename(Cart::class);
            $cartNumber = 1;
            $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
            while (Cart::where('name', $uniqueName)->exists()) {
                $cartNumber++;
                $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
            }
            $cart = Cart::create([
                'session_id' => $this->session_id,
                'name' => $uniqueName,
                'delivery_price' => app('global_delivery_price'),
                'status_id' => app('global_cart_new'),
                'currency_id' => $this->product->product_prices->first()->pricelist->currency_id,
            ]);
            $this->emit('newcart');
        }

        $cartItem = Cart_Item::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) {
            $cartItem = Cart_Item::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price' => $this->product->product_prices->first()->value,
                'quantity' => 1
            ]);
            $cart->increment('quantity_amount');
            $cart->sum_amount += $this->product->product_prices->first()->value;
            if ($cart->voucher && $cart->voucher->percent !== null) {
                $cart->voucher_value = ($cart->voucher->percent / 100) * $cart->sum_amount;
            } elseif ($cart->voucher && $cart->voucher->value !== null) {
                $cart->voucher_value = $cart->voucher->value;
            }
            $cart->final_amount = $cart->sum_amount + $cart->delivery_price;
            $cart->final_amount -= $cart->voucher_value;
        } else {
            if ($cartItem->quantity < $this->product->quantity) {
                $cartItem->increment('quantity');
                $cart->increment('quantity_amount');
                if ($cartItem->price != $this->product->product_prices->first()->value) {
                    $cartItem->price = $this->product->product_prices->first()->value;
                    $cartItem->save();
                    $sum_amount = 0;
                    foreach ($cart->carts as $item) {
                        $sum_amount = $sum_amount + $item->price * $item->quantity;
                    }
                    $cart->sum_amount = $sum_amount;
                    $cart->seen_by_customer = true;
                } else {
                    $cart->sum_amount += $this->product->product_prices->first()->value;
                }
                $cart->delivery_price = app('global_delivery_price');
                if ($cart->voucher && $cart->voucher->percent !== null) {
                    $cart->voucher_value = ($cart->voucher->percent / 100) * $cart->sum_amount;
                } elseif ($cart->voucher && $cart->voucher->value !== null) {
                    $cart->voucher_value = $cart->voucher->value;
                }
                $cart->final_amount = $cart->sum_amount + app('global_delivery_price');
                $cart->final_amount -= $cart->voucher_value;
            }
        }
        $cart->status_id = app('global_cart_new');
        $cart->save();
        $this->emit('cartUpdated');
    }
}