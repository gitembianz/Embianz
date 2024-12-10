<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\UserSessions;
use App\Models\UserPromotions;
use Illuminate\Support\Facades\DB;


class CartProductsList extends Component
{
    public $showcart = false;
    public $voucher = "";
    public $aplicabble_voucher = false;
    public $message = null;
    public $cartmodified = false;
    public $session_id;
    public $timer = 0;

    protected $listeners = [
        'showcart' => 'cartshow',
        'orderprocess' => 'orderprocess',
        'newcartlist' => 'mount',
        'cartUpdated' => 'mount',
        'timmerexpired' => 'checkpromotions',

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

    public function getPromotionsProperty()
    {
        if (app()->has('global_promotion_on') && app('global_promotion_on') === 'true') {
            $user = UserSessions::where('sessions', $this->session_id)->first();
            return $user->promotions;
        } else {
            return collect();
        }
    }

    public function getGlobalPromotionsProperty()
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



    public function getCartProperty()
    {
        if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

            $cachedProducts = app()->make('cached_products')->keyBy('id');

            $cart = Cart::select('id', 'quantity_amount', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value', 'promotion_value')
                ->where('session_id', $this->session_id)
                ->where('status_id', '!=', app('global_cart_closed'))
                ->with([
                    'voucher' => function ($query) {
                        $query->select('code', 'id', 'percent', 'value');
                    },
                    'cartItems' => function ($query) {
                        $query->select('id', 'cart_id', 'product_id', 'price', 'quantity');
                    }
                ])
                ->latest()
                ->first();

            if ($cart) {
                foreach ($cart->cartItems as $item) {
                    if ($cachedProducts->has($item->product_id)) {
                        $item->setRelation('product', $cachedProducts->get($item->product_id));
                    }
                }
            }

            return $cart;
        } else {
            return Cart::select('id', 'quantity_amount', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value', 'promotion_value')
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
                                                $query->select('path', 'name', 'type')->where('type', 'min');
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

    public function checkpromotions()
    {
        if (app()->has('global_promotion_on') && app('global_promotion_on') === "true" && $this->cart) {
            $value = 0;

            $counterpromotion = optional($this->promotions)
                ->where('promotion_type', 'counter')
                ->first();

            if ($counterpromotion && Carbon::parse($counterpromotion->promotion_expiration_date)->isFuture()) {
                $expirationDate = Carbon::parse($counterpromotion->promotion_expiration_date);
                $now = Carbon::now();

                if ($counterpromotion->promotion_value) {
                    $value += $counterpromotion->promotion_value;
                } elseif ($counterpromotion->promotion_percent) {
                    $value += $this->cart->sum_amount * ($counterpromotion->promotion_percent / 100);
                }

                $this->timer = $expirationDate->greaterThan($now)
                    ? $expirationDate->diffInSeconds($now)
                    : 0;
            } else {
                $this->timer = 0;
            }

            foreach ($this->promotions->where('promotion_type', 'amount') as $promo) {
                if ($promo->promotion_cart_amount > $this->cart->sum_amount) {
                    $promo->active = false;
                    $promo->save();
                }
                if ($promo->active) {
                    if ($promo->promotion_value) {
                        $value += $promo->promotion_value;
                    } elseif ($promo->promotion_percent) {
                        $value += $this->cart->sum_amount * ($promo->promotion_percent / 100);
                    }
                }
            }

            $this->cart->update([
                'promotion_value' => $value
            ]);

            $this->mount();
        }
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
        $this->session_id = request()->cookie('sessionId') ?? session()->getId();
    }


    public function pricechanged()
    {
        if ($this->cart) {
            foreach ($this->cart->cartItems as $item) {
                if (optional($item->product->product_prices->first())->value) {

                    if ($item->price != $item->product->product_prices->first()->value) {
                        $item->price = $item->product->product_prices->first()->value;
                        $item->save();
                        $sum_amount = 0;
                        foreach ($this->cart->cartItems as $element) {
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
                $this->emit('timmerexpired');
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
                if ($item->quantity > $item->product->quantity && (app()->has('global_preorder') && app('global_preorder') != 'true')) {
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
    public function increment($id)
    {
        if ($this->cart) {
            $cartitem_to_increment = $this->cart->cartItems()->where('id', $id)->first();
            if ($cartitem_to_increment->quantity < $cartitem_to_increment->product->quantity || (app()->has('global_preorder') && app('global_preorder') === 'true')) {
                $cartitem_to_increment->increment('quantity');
                $this->cart->increment('quantity_amount');
                $this->cart->delivery_price = app('global_delivery_price');
                if ($cartitem_to_increment->price != $cartitem_to_increment->product->product_prices->first()->value) {
                    $cartitem_to_increment->price = $cartitem_to_increment->product->product_prices->first()->value;
                    $cartitem_to_increment->save();
                    $sum_amount = 0;
                    foreach ($this->cart->carts as $item) {
                        $sum_amount = $sum_amount + $item->price * $item->quantity;
                    }
                    $this->cart->sum_amount = $sum_amount;
                    $this->cart->seen_by_customer = true;
                } else {
                    $this->cart->sum_amount += $cartitem_to_increment->product->product_prices->first()->value;
                }
                if ($this->cart->voucher && $this->cart->voucher->percent !== null) {
                    $this->cart->voucher_value = ($this->cart->voucher->percent / 100) * $this->cart->sum_amount;
                }
                $this->cart->final_amount = $this->cart->sum_amount + app('global_delivery_price');
                $this->cart->final_amount -= $this->cart->voucher_value;
                $this->cart->status_id = app('global_cart_new');
                $this->cart->save();
                $this->emit('cartUpdated');
            }
        } else {
            $this->emit('newcart');
            return;
        }
        foreach ($this->globalpromotions as $promo) {
            if ($this->cart->sum_amount >= $promo['cart_amount']) {
                $user = UserSessions::where('sessions', $this->session_id)->first();

                $this->createPromotion($user->id, $promo);
            }
        }
        $this->checkpromotions();
    }

    public function decrement($id)
    {
        if ($this->cart) {
            $cartitem_to_decrement = $this->cart->cartItems->where('id', $id)->first();
            if ($cartitem_to_decrement && $cartitem_to_decrement->quantity > 1) {
                $cartitem_to_decrement->decrement('quantity');
                $this->cart->decrement('quantity_amount');
                $this->cart->delivery_price = app('global_delivery_price');
                $this->cart->sum_amount -= $cartitem_to_decrement->product->product_prices->first()->value;
                if ($this->cart->voucher && $this->cart->voucher->percent !== null) {
                    $this->cart->voucher_value = ($this->cart->voucher->percent / 100) * $this->cart->sum_amount;
                }
                $this->cart->final_amount = $this->cart->sum_amount + app('global_delivery_price');
                $this->cart->final_amount -= $this->cart->voucher_value;
                $this->cart->status_id = app('global_cart_new');
                $this->cart->save();
                $this->emit('cartUpdated');
            } else {
                return;
            }
        } else {
            $this->emit('newcart');
            return;
        }
        $this->checkpromotions();
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
}
