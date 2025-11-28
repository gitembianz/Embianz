<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Voucher;
use Livewire\Component;
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
  private $confettiTriggered = false;

  protected $listeners = [
    'showcart' => 'cartshow',
    'orderprocess' => 'mount',
    'newcartlist' => 'mount',
    'cartUpdated' => 'mount',
    'timmerexpired' => 'checkpromotions',

  ];

  public function render()
  {
    return view('livewire.cart-products-list', [
      'cart' => $this->showcart ? $this->cart : null,
    ]);
  }

  public function mount()
  {
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();
    $this->message = null;
    $this->voucher = "";
  }

  public function getPromotionsProperty()
  {
    if (app()->has('global_promotion_on') && app('global_promotion_on') === 'true') {
      $user = UserSessions::where('sessions', $this->session_id)->first();
      if (!$user) {
        return collect();
      }
      return $user->promotions()
        ->whereHas('promotion', function ($query) {
          $query->where('active', true)
            ->where('start_date', '<=', now(config('app.timezone'))->format('Y-m-d'))
            ->where('end_date', '>=', now(config('app.timezone'))->format('Y-m-d'));
        })
        ->with('promotion')
        ->get();
    }
    return collect();
  }

  public function getGlobalPromotionsProperty()
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

  public function getCartProperty()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      $cachedProducts = app()->make('cached_products')->keyBy('id');

      $cart = Cart::select('id', 'quantity_amount', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value', 'promotion_value')
        ->where('session_id', $this->session_id)
        ->where('status_id', '!=', app('global_statuses')['cart_closed'])
        ->with([
          'voucher' => function ($query) {
            $query->select('code', 'id', 'percent', 'value', 'start_date', 'end_date');
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
        ->where('status_id', '!=', app('global_statuses')['cart_closed'])
        ->with([
          'voucher' => function ($query) {
            $query->select('code', 'id', 'percent', 'value', 'start_date', 'end_date');
          },
          'cartItems' => function ($query) {
            $query->select('id', 'cart_id', 'product_id', 'price', 'quantity')
              ->with([
                'product' => function ($query) {
                  $query->select('id', 'name', 'seo_id', 'active', 'start_date', 'end_date', 'quantity', 'preorder')
                    ->with([
                      'media' => function ($query) {
                        $query->select('path', 'name', 'type')->where('type', 'min');
                      },
                      'product_prices' => function ($query) {
                        $query->select('product_id', 'value', 'discount', 'value_no_discount');
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

    $sumAmount = $this->cart->cartItems->sum(fn($item) => $item->price * $item->quantity);
    $this->cart->update([
      'sum_amount' => $sumAmount,
      'voucher_id' => null,
      'voucher_value' => 0,
      'final_amount' => $sumAmount + $this->cart->delivery_price - $this->cart->promotion_value,
      'status_id' => app('global_statuses')['cart_new'],
      'updated_at' => now(config('app.timezone')),
    ]);
    $this->message = null;
    $this->voucher = "";
    $this->emit('cartUpdated');
  }

  private function calculateVoucherValue($voucher, $sumAmount)
  {
    return $voucher->percent !== null
      ? ($voucher->percent / 100) * $sumAmount
      : min($voucher->value, $sumAmount);
  }

  public function checkvoucher()
  {
    if (!$this->cart) {
      $this->message = null;
      $this->emit('newcart');
      return;
    }
    $voucher = Voucher::where('code', $this->voucher)
      ->where('status_id', app('global_statuses')['voucher_active'])
      ->whereDate('start_date', '<=', now(config('app.timezone')))
      ->whereDate('end_date', '>=', now(config('app.timezone')))
      ->first();
    if (!$voucher) {
      $this->message = "Voucher-ul '" . $this->voucher . "' nu a fost găsit!";
      $this->voucher = "";
      return false;
    }
    $this->cart->load('cartItems');
    $sumAmount = $this->cart->cartItems->sum(fn($item) => $item->price * $item->quantity);
    $discount = $this->calculateVoucherValue($voucher, $sumAmount);
    $this->cart->update([
      'sum_amount' => $sumAmount,
      'voucher_id' => $voucher->id,
      'voucher_value' => $discount,
      'final_amount' => $sumAmount + $this->cart->delivery_price - $discount - $this->cart->promotion_value,
      'updated_at' => now(config('app.timezone')),
    ]);
    $this->message = null;
    $this->voucher = "";
    $this->emit('cartUpdated');
    return true;
  }

  protected function calculatePromoValue($promo, $amount)
  {
    if ($promo->promotion_value) {
      return $promo->promotion_value;
    }
    if ($promo->promotion_percent) {
      return round($amount * ($promo->promotion_percent / 100), 2);
    }
    return 0;
  }

  public function checkpromotions()
  {
    if (!app()->has('global_promotion_on') || app('global_promotion_on') !== "true" || !$this->cart) {
      return;
    }
    $sumAmount = $this->cart->cartItems->sum(fn($item) => $item->price * $item->quantity);
    $promotionValue = 0;
    $this->timer = 0;
    // Handle 'counter' promotion
    $counter = optional($this->promotions)->firstWhere('promotion_type', 'counter');
    if ($counter && Carbon::parse($counter->promotion_expiration_date)->isFuture()) {
      $expiration = Carbon::parse($counter->promotion_expiration_date);
      $this->timer = $expiration->diffInSeconds(now(config('app.timezone')));

      $promotionValue += $this->calculatePromoValue($counter, $sumAmount);
    }
    // Handle 'amount' promotions (non-mutating)
    foreach ($this->promotions->where('promotion_type', 'amount') as $promo) {
      if (
        $promo->promotion_cart_amount <= $sumAmount &&
        $promo->active
      ) {
        $promotionValue += $this->calculatePromoValue($promo, $sumAmount);
      }
    }
    $this->cart->update([
      'sum_amount' => $sumAmount,
      'promotion_value' => $promotionValue,
      'final_amount' => $sumAmount + $this->cart->delivery_price - $promotionValue - $this->cart->voucher_value,
    ]);
  }

  public function seen()
  {
    $this->cart->seen_by_customer = false;
    $this->cart->save();
    $this->cartmodified = false;

    return;
  }

  public function pricechanged()
  {
    if (!$this->cart) {
      return;
    }

    $priceChanged = false;
    $this->cart->load(['cartItems.product.product_prices']);

    foreach ($this->cart->cartItems as $item) {
      $latestPrice = optional($item->product->product_prices->first())->value;

      if ($latestPrice === null) {
        continue;
      }

      if ($item->price != $latestPrice) {
        $item->price = $latestPrice;
        $item->save();
        $priceChanged = true;
      }
    }

    if ($priceChanged) {
      $sumAmount = $this->cart->cartItems->sum(fn($i) => $i->price * $i->quantity);

      $voucherValue = 0;
      if (
        $this->cart->voucher &&
        $this->cart->voucher->start_date <= now(config('app.timezone'))->format('Y-m-d') &&
        $this->cart->voucher->end_date >= now(config('app.timezone'))->format('Y-m-d')
      ) {

        if ($this->cart->voucher->percent !== null) {
          $voucherValue = round($sumAmount * ($this->cart->voucher->percent / 100), 2);
        } elseif ($this->cart->voucher->value !== null) {
          $voucherValue = $this->cart->voucher->value;
        }
      }

      $this->cart->sum_amount = $sumAmount;
      $this->cart->voucher_value = $voucherValue;
      $this->cart->final_amount = $sumAmount + $this->cart->delivery_price - $voucherValue - $this->cart->promotion_value;

      if (
        app()->has('global_customer_cart_notification') &&
        app('global_customer_cart_notification') === "true"
      ) {
        $this->cart->seen_by_customer = true;
        $this->cartmodified = true;
      }

      $this->cart->save();
    }
  }

  public function cartshow()
  {
    $this->showcart = true;
  }

  public function removeFromCart($productId)
  {
    if (!$this->cart || !$this->cart->id) {
      return;
    }

    $this->cart->load(['cartItems', 'voucher']); // eager load to avoid N+1

    $cartItem = $this->cart->cartItems->firstWhere('product_id', $productId);

    if (!$cartItem) {
      return;
    }

    // Remove item
    $amountToSubtract = $cartItem->price * $cartItem->quantity;
    $quantityToSubtract = $cartItem->quantity;

    $cartItem->delete();

    // Refresh cartItems after deletion
    $this->cart->load('cartItems');

    // Recalculate sum and quantity
    $sumAmount = $this->cart->cartItems->sum(fn($item) => $item->price * $item->quantity);
    $quantityAmount = $this->cart->cartItems->sum('quantity');

    // Recalculate voucher value
    $voucherValue = 0;
    if (
      $this->cart->voucher &&
      $this->cart->voucher->start_date <= now(config('app.timezone'))->format('Y-m-d') &&
      $this->cart->voucher->end_date >= now(config('app.timezone'))->format('Y-m-d')
    ) {

      if ($this->cart->voucher->percent !== null) {
        $voucherValue = round($sumAmount * ($this->cart->voucher->percent / 100), 2);
      } elseif ($this->cart->voucher->value !== null) {
        $voucherValue = $this->cart->voucher->value;
      }
    }else{
      $this->removevoucher();
    }

    // If cart is empty, clear voucher info
    if ($quantityAmount == 0) {
      $this->cart->voucher_id = null;
      $voucherValue = 0;
    }

    $this->cart->quantity_amount = $quantityAmount;
    $this->cart->sum_amount = $sumAmount;
    $this->cart->voucher_value = $voucherValue;
    $this->cart->final_amount = $sumAmount + $this->cart->delivery_price - $voucherValue - $this->cart->promotion_value;
    $this->cart->status_id = app('global_statuses')['cart_new'];
    $this->cart->updated_at = now(config('app.timezone'));
    $this->cart->save();

    $this->emit('cartUpdated');
    $this->emit('timmerexpired');
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
    if (!empty($this->voucher)) {
      $this->aplicabble_voucher = true;
      return;
    }

    if (!$this->cart || $this->cart->quantity_amount == 0) {
      return;
    }

    $this->cart->load('cartItems.product');

    $today = now(config('app.timezone'))->toDateString();
    $errorMessage = app()->has('global_order_error_quantity')
      ? app('global_order_error_quantity')
      : "Vă rog verificați detaliile comenzii!";

    foreach ($this->cart->cartItems as $item) {
      $product = $item->product;

      if (
        !$product->active ||
        $product->start_date > $today ||
        $product->end_date < $today
      ) {
        $this->emit('cartUpdated');
        return;
      }

      if ($item->quantity > $product->quantity && !$product->preorder) {
        $this->dispatchBrowserEvent('alert__modal', ['message' => $errorMessage]);
        return;
      }
    }

    $this->cart->update([
      'status_id' => app('global_statuses')['cart_checkout'],
    ]);

    return redirect()->route('order');
  }

  public function increment($id)
  {
    if (!$this->cart) {
      $this->emit('newcart');
      return;
    }

    // Eager load once for all needed relationships
    $this->cart->loadMissing('cartItems.product.product_prices', 'voucher');

    $cartItem = $this->cart->cartItems->firstWhere('id', $id);
    if (!$cartItem) return;

    $product = $cartItem->product;
    $price = $product->product_prices->first()?->value ?? 0;

    // Check stock or preorder
    if ($cartItem->quantity >= $product->quantity && !$product->preorder) {
      return;
    }

    // Increment quantities
    $cartItem->increment('quantity');
    $this->cart->increment('quantity_amount');

    // Price mismatch: update and recalculate full sum
    if ($cartItem->price != $price) {
      $cartItem->price = $price;
      $this->cart->seen_by_customer = true;
      $cartItem->save();
    }

    $this->cart->delivery_price = app('global_delivery_price');

    // Recalculate voucher value
    $sumAmount = $this->cart->cartItems->sum(fn($i) => $i->price * $i->quantity);

    $voucherValue = 0;
    if (
      $this->cart->voucher &&
      $this->cart->voucher->start_date <= now(config('app.timezone'))->format('Y-m-d') &&
      $this->cart->voucher->end_date >= now(config('app.timezone'))->format('Y-m-d')
    ) {

      if ($this->cart->voucher->percent !== null) {
        $voucherValue = round($sumAmount * ($this->cart->voucher->percent / 100), 2);
      } elseif ($this->cart->voucher->value !== null) {
        $voucherValue = $this->cart->voucher->value;
      }
    }else{
      $this->removevoucher();
    }

    $this->cart->sum_amount = $sumAmount;
    $this->cart->voucher_value = $voucherValue;
    $this->cart->final_amount = $sumAmount + $this->cart->delivery_price - $voucherValue - $this->cart->promotion_value;

    $this->cart->status_id = app('global_statuses')['cart_new'];
    $this->cart->save();

    $this->emit('cartUpdated');

    // Promotions
    foreach ($this->globalpromotions as $promo) {
      if ($this->cart->sum_amount >= $promo['cart_amount']) {
        if ($user = UserSessions::where('sessions', $this->session_id)->first()) {
          $this->createPromotion($user->id, $promo);
        }
      }
    }

    $this->checkpromotions();
  }


  public function decrement($id)
  {
    if (!$this->cart) {
      $this->emit('newcart');
      return;
    }

    // Load relationships to avoid N+1 queries
    $this->cart->loadMissing('cartItems.product.product_prices', 'voucher');

    $cartItem = $this->cart->cartItems->firstWhere('id', $id);

    if (!$cartItem || $cartItem->quantity <= 1) {
      return;
    }

    $product = $cartItem->product;
    $price = $product->product_prices->first()?->value ?? 0;

    // Decrement item and cart quantity
    $cartItem->decrement('quantity');
    $this->cart->decrement('quantity_amount');

    // Price mismatch: update and recalculate full sum
    if ($cartItem->price != $price) {
      $cartItem->price = $price;
      $this->cart->seen_by_customer = true;
      $cartItem->save();
    }

    $this->cart->delivery_price = app('global_delivery_price');

    // Recalculate voucher value
    $sumAmount = $this->cart->cartItems->sum(fn($i) => $i->price * $i->quantity);

    $voucherValue = 0;
    if (
      $this->cart->voucher &&
      $this->cart->voucher->start_date <= now(config('app.timezone'))->format('Y-m-d') &&
      $this->cart->voucher->end_date >= now(config('app.timezone'))->format('Y-m-d')
    ) {

      if ($this->cart->voucher->percent !== null) {
        $voucherValue = round($sumAmount * ($this->cart->voucher->percent / 100), 2);
      } elseif ($this->cart->voucher->value !== null) {
        $voucherValue = $this->cart->voucher->value;
      }
    }else{
      $this->removevoucher();
    }

    $this->cart->sum_amount = $sumAmount;
    $this->cart->voucher_value = $voucherValue;
    $this->cart->final_amount = $sumAmount + $this->cart->delivery_price - $voucherValue - $this->cart->promotion_value;

    $this->cart->status_id = app('global_statuses')['cart_new'];
    $this->cart->save();

    $this->emit('cartUpdated');

    $this->checkpromotions();
  }


  private function createPromotion($userId, $promo)
  {
    if ($this->confettiTriggered) {
      return;
    }

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
        "active" => true, // Always set 'active' to true
      ]
    );

    if (!$existingPromotion) {
      $message = app()->has('label_confetti_modal_text') ? app('label_confetti_modal_text') : "Ai primit din partea noastra o reducere! Felicitari";

      $this->dispatchBrowserEvent('confettialert__modal', ['message' => $message]);

      $this->confettiTriggered = true;

      usleep(200000);

      $this->confettiTriggered = false;
    }
  }
}
