<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\UserSessions;
use App\Models\UserPromotions;

class AddToCartButton extends Component
{
    public $product;
    public $session_id;

    public function mount($product)
    {
        $this->product = $product;
        $this->session_id = request()->cookie('sessionId') ?? session()->getId();
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
    public function getPromotionsProperty()
    {
        if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {

            return collect(app()->make('promotions'))
                ->filter(function ($promotion) {
                    return isset($promotion['start_date'], $promotion['end_date'], $promotion['type']) && // Ensure keys exist
                        $promotion['start_date'] <= now()->format('Y-m-d') &&
                        $promotion['end_date'] >= now()->format('Y-m-d') &&
                        $promotion['type'] === 'amount';
                });
        } else {
            return collect();
        }
    }
    private function createPromotion($userId, $promo)
    {
        // Check if the promotion already exists
        $existingPromotion = UserPromotions::where('session_id', $userId)
            ->where('promotion_id', $promo['id'])
            ->first();

        // Create or update the promotion
        $promotion = UserPromotions::updateOrCreate(
            [
                'session_id' => $userId,
                'promotion_id' => $promo['id'],
            ],
            [
                "promotion_type" => $promo['type'],
                "promotion_cookieid" => $promo['cookieid'],
                "promotion_start_date" => $promo['start_date'],
                "promotion_expiration_date" => $promo['end_date'],
                "promotion_cooldown_timer" => $promo['cooldown_timer'],
                "promotion_cart_amount" => $promo['cart_amount'],
                "promotion_value" => $promo['promotion_value'],
                "promotion_percent" => $promo['promotion_percent'],
                "active" => true, // Always set 'active' to true
            ]
        );

        // If the promotion was newly created, emit the event
        if (!$existingPromotion) {
            $message = app()->has('label_confetti_modal_text') ? app('label_confetti_modal_text') : "Ai primit din partea noastra o reducere! Felicitari";

            $this->dispatchBrowserEvent('confettialert__modal', ['message' => $message]);
        }
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

            // Get the last cart name and calculate the next cart number
            $lastCart = Cart::latest('id')->first();
            $cartNumber = $lastCart ? ((int)str_replace("{$baseName}_", '', $lastCart->name) + 1) : 1;

            // Generate the unique name
            $uniqueName = "{$baseName}_" . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);

            // Create the cart
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
                'quantity' => 1,
                'vat' => $this->product->product_prices->first()->vat
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
            if ($cartItem->quantity < $this->product->quantity || (app()->has('global_preorder') && app('global_preorder') === 'true')) {
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
                if (!$cartItem->vat) {
                    $cartItem->vat = $this->product->product_prices->first()->vat;
                    $cartItem->save();
                }
            }
        }
        $cart->status_id = app('global_cart_new');
        $cart->save();
        foreach ($this->promotions as $promo) {
            if ($cart->sum_amount >= $promo['cart_amount']) {
                $user = UserSessions::where('sessions', $this->session_id)->first();

                $this->createPromotion($user->id, $promo);
            }
        }
        $this->emit('cartUpdated');
    }
}
