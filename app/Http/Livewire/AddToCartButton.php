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
  // This method retrieves promotions that are currently active and of type 'amount'.
  public function getPromotionsProperty()
  {
    if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {

      return collect(app()->make('promotions'))
        ->filter(function ($promotion) {
          return isset($promotion['start_date'], $promotion['end_date'], $promotion['type']) && // Ensure keys exist
            $promotion['start_date'] <= now(config('app.timezone'))->format('Y-m-d') &&
            $promotion['end_date'] >= now(config('app.timezone'))->format('Y-m-d') &&
            $promotion['type'] === 'amount';
        });
    } else {
      return collect();
    }
  }
  // This method creates or updates a promotion for the user based on the provided promotion data.
  private function createPromotion($userId, $promo)
  {
    $existingPromotion = UserPromotions::where('session_id', $userId)
      ->where('promotion_id', $promo['id'])
      ->first();

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
        "active" => true,
      ]
    );

    if (!$existingPromotion) {
      $message = app()->has('label_confetti_modal_text') ? app('label_confetti_modal_text') : "Ai primit din partea noastra o reducere! Felicitari";

      $this->dispatchBrowserEvent('confettialert__modal', ['message' => $message]);
    }
  }

  public function addToCart($productId)
  {
    $cart = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_statuses')['cart_closed'])
      ->with('voucher')
      ->latest()
      ->first();

    if (!$cart) {
      $baseName = class_basename(Cart::class);
      $lastCart = Cart::latest('id')->first();
      $cartNumber = $lastCart ? ((int)str_replace("{$baseName}_", '', $lastCart->name) + 1) : 1;
      $uniqueName = "{$baseName}_" . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);

      $cart = Cart::create([
        'session_id' => $this->session_id,
        'name' => $uniqueName,
        'delivery_price' => app('global_delivery_price'),
        'delivery_price_vat' => app()->bound('global_delivery_price_vat') ? app('global_delivery_price_vat') : 19,
        'status_id' => app('global_statuses')['cart_new'],
        'currency_id' => $this->product->product_prices->first()->pricelist->currency_id,
      ]);

      $this->emit('newcart');
    }

    $price = $this->product->product_prices->first();
    $cartItem = Cart_Item::firstOrNew([
      'cart_id' => $cart->id,
      'product_id' => $productId,
    ]);

    if (!$cartItem->exists) {
      $cartItem->price = $price->value;
      $cartItem->quantity = 1;
      $cartItem->vat = $price->vat;
      $cartItem->save();
    } else {
      if ($cartItem->quantity < $this->product->quantity || $this->product->preorder) {
        $cartItem->quantity++;
        if ($cartItem->price != $price->value) {
          $cartItem->price = $price->value;
          $cart->seen_by_customer = true;
        }
        if (!$cartItem->vat) {
          $cartItem->vat = $price->vat;
        }
        $cartItem->save();
      }
    }

    // Recalculate cart sum_amount
    $sumAmount = $cart->cartItems->sum(fn($item) => $item->price * $item->quantity);
    $cart->sum_amount = $sumAmount;

    // Voucher logic
    $cart->voucher_value = 0;
    if ($cart->voucher) {
      $cart->voucher_value = $cart->voucher->percent !== null
        ? ($cart->voucher->percent / 100) * $cart->sum_amount
        : ($cart->voucher->value ?? 0);
    }

    $cart->quantity_amount += 1;
    $cart->final_amount = $cart->sum_amount + $cart->delivery_price - $cart->voucher_value - $cart->promotion_value;
    $cart->status_id = app('global_statuses')['cart_new'];
    $cart->seen_by_customer = true;
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
