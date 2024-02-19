<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Cart_Item;
use Livewire\Component;

class ProductDetails extends Component
{

    public $activeTab = 0;
    public $quantity;
    public $limit = null;
    public $maxlimit = null;
    public $product;
    public $session_id;

    public function render()
    {
        return view('livewire.product-details');
    }
    public function mount($product)
    {
        $this->product = $product;
        $this->quantity = 1;
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
    public function switchTab($index)
    {
        $this->activeTab = $index;
    }
    public function updateCounterValue()
    {
        $this->quantity = $this->quantity;
    }

    public function incrementCounter()
    {
        $this->limit = $this->product->quantity;
        if ($this->quantity >= $this->limit) {
            $this->maxlimit = true;
            $this->quantity = $this->limit;
        } else {
            $this->quantity++;
        }
    }
    public  function decrementCounter()
    {

        if ($this->quantity > 1) {
            if ($this->quantity == $this->limit) {
                $this->maxlimit = false;
            }
            $this->quantity--;
        }
    }
    public function addToCart($productId)
    {
        $cart = Cart::where('session_id', $this->session_id)->where('status_id', '!=', app('global_cart_closed'))->latest()->first();

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

        $cartItem = Cart_Item::where('cart_id', $cart->id)->where('product_id', $productId)->first();

        if (!$cartItem) {
            $cartItem = Cart_Item::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price' => $this->product->product_prices->first()->value,
                'quantity' => $this->quantity
            ]);

            $cart->quantity_amount += $this->quantity;
            $cart->delivery_price = app('global_delivery_price');
            $cart->sum_amount += ($this->product->product_prices->first()->value * $this->quantity);
            if ($cart->voucher && $cart->voucher->percent !== null) {
                $cart->voucher_value = ($cart->voucher->percent / 100) * $cart->sum_amount;
            }
            $cart->final_amount = $cart->sum_amount + app('global_delivery_price');
            $cart->final_amount -= $cart->voucher_value;
        } else {
            if (($cartItem->quantity + $this->quantity) < $this->product->quantity) {
                $cartItem->quantity += $this->quantity;
                $cartItem->save();
                $cart->quantity_amount += $this->quantity;
                $cart->delivery_price = app('global_delivery_price');
                $cart->sum_amount += ($this->product->product_prices->first()->value * $this->quantity);
                if ($cart->voucher && $cart->voucher->percent !== null) {
                    $cart->voucher_value = ($cart->voucher->percent / 100) * $cart->sum_amount;
                }
                $cart->final_amount = $cart->sum_amount + app('global_delivery_price');
                $cart->final_amount -= $cart->voucher_value;
            }
        }
        $cart->save();
        $this->quantity = 1;
        $this->emit('cartUpdated');
    }
}
