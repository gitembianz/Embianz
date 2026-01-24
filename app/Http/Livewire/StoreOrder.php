<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\County;
use App\Models\Account;
use App\Models\Address;
use App\Models\Country;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Order_Item;
use App\Models\UserSessions;
use Stripe\Checkout\Session;
use App\Mail\ConfirmationOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Models\Store_Settings;

class StoreOrder extends Component
{
  public $step = 1;
  public $is_account = null;
  public $back = false;
  public $terms = false;
  public $subscribe = false;
  public $errorterms = false;
  public $session_id;
  public $cash;
  public $card;
  public $ordin;
  public $orderNumber;
  public $payment_cancel = false;
  public $new_order;
  public $modification = false;
  public $country;
  public array $countries = [];

  // individual declaration
  public $individual = true;
  public $is_identic = true;
  public $juridic = false;

  public $billing_first;
  public $billing_last;
  public $billing_phone;
  public $billing_email;
  public $billing_company_name;
  public $billing_registration_code;
  public $billing_registration_number;
  public $billing_bank;
  public $billing_account;
  public $billing_address1;
  public $billing_address2;
  public $billing_country;
  public $billing_county;
  public $billing_city;
  public $billing_zipcode;
  public $shipping_first;
  public $shipping_last;
  public $shipping_phone;
  public $shipping_email;
  public $shipping_address1;
  public $shipping_address2;
  public $shipping_country;
  public $shipping_county;
  public $shipping_city;
  public $shipping_zipcode;

  public $rtc = false;
  public $crd = false;
  public $invoice = false;
  public $validatequantity = true;
  public $payment;

  protected $listeners = [
    'nocard' => 'mount',
    'cartUpdated' => 'mount',
    'isdisabled' => 'checkIsDisabled',
    'timmerexpired' => 'checkpromotions',
    'saveFormToSession' => 'updateFormSession',
    'countdownExpired' => 'mount',
  ];

  public function render()
  {
    if ($this->step == 2) {
      $data = [
        'cart' => $this->cart
      ];
    } elseif ($this->step == 3) {
      $data = [
        'order' => $this->new_order
      ];
    } else {
      $data = [];
    }

    return view('livewire.store-order', $data);
  }

  public function mount()
  {

    $this->modification = false;
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();
    if (app()->has("global_check_terms_order") && app('global_check_terms_order') === 'true') {
      $this->terms = true;
    }
    $this->countries = Cache::get('active_countries', []);


    if (session()->has('paymentcancel')) {
      $this->payment_cancel = true;
      $this->step = 2;
      $message = app('label_order_payment_cancel_text') ?? "";
      $this->emit('alert__modal', ['message' => $message]);
      session()->forget('paymentcancel');
    }


    if (session()->has('paymentsucces')) {

      if ($this->cart) {

        if ($this->cart->voucher && $this->cart->voucher->single_use) {
          Voucher::where('id', $this->cart->voucher_id)->update([
            'status_id' => app('global_statuses')['voucher_closed']
          ]);
        }
        $this->cart->update([
          'status_id' => app('global_statuses')['cart_closed']
        ]);
        $order = Order::where('session_id', $this->session_id)->where('status_id', app('global_statuses')['order_check_payment'])->first();
        $order->status_id = app('global_statuses')['order_processing'];
        $this->orderNumber = $order->order_number;
        $order->save();
        $this->new_order = $order;
        foreach ($order->orders as $item) {
          $item->product->quantity -= $item->quantity;
          $item->product->save();
        }
        $this->step = 3;
      } else {
        $this->step = 1;
        $this->emit('alert__modal', ['message' => 'Your cart is empty!']);
      }

      session()->forget('paymentsucces');
      try {
        Mail::to($order->account->email)->send(new ConfirmationOrder($order));
      } catch (\Throwable $th) {
        return;
      }
    }

    if (session()->has('form_data')) {
      $formData = session('form_data');
      foreach ($formData as $key => $value) {
        $this->{$key} = $value;
      }
    } else {
      if (request()->cookie('accountId')) {
        $this->is_account = request()->cookie('accountId');
      }

      if ($this->is_account != null) {
        $account = Account::with('addresses')->find($this->is_account) ?? null;
        if (!$account) {
          unset($_COOKIE['accountId']);
          $this->is_account = null;
        } else {
          cookie()->queue(cookie()->forget('accountId'));
          $this->billing_first = $account->first_name;
          $this->billing_last = $account->last_name;
          $this->billing_phone = $account->phone;
          $this->billing_email = $account->email;
          $this->billing_address1 = optional(optional($account->addresses->where('is_default', true)->last())->billing)->address1;
          $this->billing_address2 = optional(optional($account->addresses->where('is_default', true)->last())->billing)->address2;
          $this->billing_country = optional(optional($account->addresses->where('is_default', true)->last())->billing)->country;
          $this->billing_county = optional(optional($account->addresses->where('is_default', true)->last())->billing)->county;
          $this->billing_city = optional(optional($account->addresses->where('is_default', true)->last())->billing)->city;
          $this->billing_zipcode = optional(optional($account->addresses->where('is_default', true)->last())->billing)->zipcode;

          $this->shipping_first = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->first_name;
          $this->shipping_last = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->last_name;
          $this->shipping_phone = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->phone;
          $this->shipping_email = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->email;
          $this->shipping_address1 = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->address1;
          $this->shipping_address2 = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->address2;
          $this->shipping_country = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->country;
          $this->shipping_county = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->county;
          $this->shipping_city = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->city;
          $this->shipping_zipcode = optional(optional($account->addresses->where('is_default', true)->last())->shipping)->zipcode;


          if ($account->type == 'individual') {

            $this->individual = true;
            $this->juridic = false;
          } else {

            $this->juridic = true;
            $this->individual = false;

            $this->billing_company_name = $account->company_name;
            $this->billing_registration_code = $account->registration_code;
            $this->billing_registration_number = $account->registration_number;
            $this->billing_bank = $account->bank_name;
            $this->billing_account = $account->account;
          }
        }
      } else {
        $this->country = collect($this->countries)->firstWhere('name', app('global_default_country')) ?? 'n/a';
        if ($this->country != 'n/a') {

          $this->billing_country = $this->country['name'] ?? app('global_default_country');
          $this->shipping_country = $this->country['name'] ?? app('global_default_country');
        } else {
          $this->billing_country = 'Romania';
          $this->shipping_country = 'Romania';
        }
      }
    }

    $this->cash  = app()->has('global_cash') ? app('global_cash') : null;
    $this->card  = app()->has('global_card_stripe') ? app('global_card_stripe') : null;
    $this->ordin = app()->has('global_ordin') ? app('global_ordin') : null;

    if (app()->has('global_default_payment') && app('global_default_payment') === "card") {

      $this->payment = $this->card;
      $this->payment['description'] = app('label_order_cart_stripe_title');
      $this->crd = true;
    } else {
      $this->rtc = true;
      $this->payment = $this->cash;
      $this->payment['description'] = app('label_order_cash_title');
    }


    if ($this->step == 2) {
      $this->cart->update([
        'status_id' => app('global_statuses')['cart_checkoutdetails']
      ]);
      $this->validatequantity = true;
    }
  }

  public function getPromotionsProperty()
  {
    if (app()->has('global_promotion_on') && app('global_promotion_on') === 'true') {
      $user = UserSessions::where('sessions', $this->session_id)->first();
      return $user ? $user->promotions : collect();
    } else {
      return collect();
    }
  }

  public function checkpromotions()
  {
    if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {
      $value = 0;

      $counterpromotion = optional($this->promotions)
        ->where('promotion_type', 'counter')
        ->first();

      if ($counterpromotion && Carbon::parse($counterpromotion->promotion_expiration_date)->isFuture()) {

        if ($counterpromotion->promotion_value && $this->cart) {
          $value += $counterpromotion->promotion_value;
        } elseif ($counterpromotion->promotion_percent && $this->cart) {
          $value += $this->cart->sum_amount * ($counterpromotion->promotion_percent / 100);
        }
      }
      if ($this->cart->status_id === app('global_statuses')['cart_new']) {

        $this->cart->update([
          'promotion_value' => $value
        ]);
      }

      $this->mount();
    }
  }

  public function checkIsDisabled()
  {
    $this->modification = true;
  }

  // get cart property with childs
  public function getHasCartWithItemsProperty()
  {
    if ($this->step == 3) {
      return true;
    }

    return Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_statuses')['cart_closed'])
      ->has('cartItems')
      ->exists();
  }

  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount', 'currency_id', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value', 'status_id', 'promotion_value')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_statuses')['cart_closed'])
      ->with([
        'voucher' => function ($query) {
          $query->select('code', 'id', 'percent', 'single_use', 'value', 'start_date', 'end_date');
        },
        'cartItems' => function ($query) {
          $query->select('id', 'cart_id', 'product_id', 'price', 'quantity', 'vat')
            ->with([
              'product' => function ($query) {
                $query->select('id', 'preorder', 'name', 'seo_id', 'quantity', 'active', 'start_date', 'end_date')
                  ->with([
                    'media' => function ($query) {
                      $query->select('path', 'name', 'type')->where('type', 'min');
                    },
                    'product_prices' => function ($query) {
                      $query->select('product_id', 'value', 'pricelist_id')
                        ->with(['pricelist' => function ($query) {
                          $query->select('id', 'currency_id');
                        }]);
                    }
                  ]);
              }
            ]);
        }
      ])
      ->latest()
      ->first() ?? null;
  }

  public function previous()
  {
    $this->step--;
    $this->resetErrorBag();
  }

  public function next()
  {
    if (!$this->cart->cartItems() || !$this->cart) {
      $this->back = true;
    } else {
      cookie()->queue(cookie()->forget('accountId'));
      $this->resetErrorBag();
      if ($this->is_identic) {
        $this->billing_first = $this->shipping_first;
        $this->billing_last = $this->shipping_last;
        $this->billing_phone = $this->shipping_phone;
        $this->billing_email = $this->shipping_email;
        $this->billing_address1 = $this->shipping_address1;
        $this->billing_address2 = $this->shipping_address2;
        $this->billing_country = $this->shipping_country;
        $this->billing_county = $this->shipping_county;
        $this->billing_city = $this->shipping_city;
        $this->billing_zipcode = $this->shipping_zipcode;
      }

      $this->validateData();
      $this->updateFormSession();
      $this->step = 2;

      $this->cart->update([
        'status_id' => app('global_statuses')['cart_checkoutdetails']
      ]);

      if ($this->is_identic && $this->step == 2) {
        $this->dispatchBrowserEvent('refreshBillingFields');
      }

      $this->dispatchBrowserEvent('goup');
    }
  }

  public function validateData()
  {
    try {
      $rules = [
        'shipping_first'   => 'required|string|min:2|max:50',
        'shipping_last'    => 'required|string|min:2|max:50',
        'shipping_address1' => 'required|string|min:2|max:100',
        'shipping_city'    => 'required|string|min:2|max:50',
        'shipping_county'  => 'required|string|min:2|max:50',
        'shipping_phone'   => ['required', 'regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/'],
        'shipping_email'   => 'required|email',
      ];

      if (!$this->is_identic) {
        $billing = [
          'billing_first'   => 'required|string|min:2|max:50',
          'billing_last'    => 'required|string|min:2|max:50',
          'billing_address1' => 'required|string|min:2|max:100',
          'billing_city'    => 'required|string|min:2|max:50',
          'billing_county'  => 'required|string|min:2|max:50',
          'billing_phone'   => ['required', 'regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/'],
          'billing_email'   => 'required|email',
        ];
        $rules = array_merge($rules, $billing);
      }

      if ($this->juridic) {
        $juridicRules = [
          'billing_company_name'       => 'required|string|min:2|max:100',
          'billing_registration_code'  => 'required|string|min:2|max:50',
          'billing_registration_number' => 'required|string|min:1|max:50',
        ];
        $rules = array_merge($rules, $juridicRules);
      }

      $this->validate($rules);
    } catch (\Illuminate\Validation\ValidationException $e) {
      $errors = $e->errors();

      $this->dispatchBrowserEvent('validation-failed', [
        'errors' => $errors
      ]);

      throw $e;
    }
  }


  public function togglepayment($item)
  {
    if ($item == 'rtc') {
      $this->payment = $this->cash;
      $this->payment['description'] = app('label_order_cash_title');
      $this->rtc = true;
      $this->crd = false;
      $this->invoice = false;
    }
    if ($item == 'crd') {
      $this->payment = $this->card;
      $this->payment['description'] = app('label_order_cart_stripe_title');
      $this->crd = true;
      $this->rtc = false;
      $this->invoice = false;
    }
    if ($item == 'invoice') {
      $this->payment = $this->ordin;
      $this->payment['description'] = app('label_order_invoice_title');
      $this->rtc = false;
      $this->crd = false;
      $this->invoice = true;
    }
  }

  public function updateFormSession()
  {
    $formFields = collect(get_object_vars($this))->filter(function ($_, $key) {
      return str_contains($key, 'billing_') || str_contains($key, 'shipping_');
    });

    $countryFields = [
      'billing_country',
      'shipping_country',
      'billing_country',
      'shipping_country',
    ];

    foreach ($countryFields as $field) {
      if (!isset($formFields[$field]) && property_exists($this, $field)) {
        $formFields[$field] = $this->{$field} ?? app('global_default_country');
      }
    }

    session()->put('form_data', $formFields->toArray());
  }

  protected function findOrCreateAddress(array $data)
  {
    $checkData = collect($data)->except(['is_default'])->toArray();

    $address = Address::where('account_id', $data['account_id'])
      ->where('type', $data['type'])
      ->where($checkData)
      ->first();

    if ($address) {
      $address->update(['is_default' => true]);
      return $address;
    }

    return Address::create($data);
  }



  public function confirm()
  {
    if (!$this->terms) {
      $this->errorterms = true;
      $this->dispatchBrowserEvent('terms__error');
      return;
    }

    if ($this->cart->cartItems && ($this->cart->status_id == app('global_statuses')['cart_checkoutdetails'])) {
      if ($this->cart->voucher) {
        $voucher = $this->cart->voucher;
        $currentDate = now(config('app.timezone'))->format('Y-m-d');
        if ($voucher->status_id == app('global_statuses')['voucher_closed'] || $voucher->start_date > $currentDate || $voucher->end_date < $currentDate) {
          $message = app('label_order_error_voucher') ?? "";
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          $this->cart->update([
            'final_amount' => ($this->cart->sum_amount + app('global_delivery_price')) - $this->cart->promotion_value,
            'voucher_id' => null,
            'voucher_value' => 0,
            'updated_at' => now(config('app.timezone')),
          ]);
          return;
        }
        if ($voucher->single_use) {
          $voucher->update(['status_id' => app('global_statuses')['voucher_closed']]);
        }
      }

      foreach ($this->cart->cartItems as $item) {
        $product = $item->product;
        $currentDate = now(config('app.timezone'))->format('Y-m-d');

        if ($item->quantity > $product->quantity && !$product->preorder) {
          $this->validatequantity = false;
          $message = app('label_order_error_quantity') ?? "";
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          return;
        }

        if (!$product->active || $product->start_date > $currentDate || $product->end_date < $currentDate) {
          $message = app('label_order_error_active') ?? "";
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          return;
        }
      }
    } else {
      $this->emit('cartUpdated');
      return;
    }

    if ($this->validatequantity) {

      $accountData = [
        'phone' => $this->is_identic ? $this->shipping_phone : $this->billing_phone,
        'email' => $this->is_identic ? $this->shipping_email : $this->billing_email,
        'name' => $this->billing_first . " " . $this->billing_last,
        'first_name' => $this->billing_first,
        'last_name' => $this->billing_last,
        'subscribe' => $this->subscribe,
      ];

      if ($this->individual) {
        $accountData = array_merge($accountData, [
          'type' => 'individual',
        ]);
      } else {
        $accountData = array_merge($accountData, [
          'type' => 'juridic',
          'company_name' => $this->billing_company_name,
          'registration_code' => $this->billing_registration_code,
          'registration_number' => $this->billing_registration_number,
          'bank_name' => $this->billing_bank,
          'account' => $this->billing_account,
        ]);
      }

      $account = Account::updateOrCreate(
        ['email' => $accountData['email']],
        
        $accountData
      );
      cookie()->queue(cookie()->make('accountId', $account->id, 60 * 24 * 30));

      Address::where('account_id', $account->id)
        ->where('is_default', true)
        ->update(['is_default' => false]);
      $billingAddressData = [
        'account_id' => $account->id,
        'first_name' => $this->billing_first,
        'last_name' => $this->billing_last,
        'phone' => $this->billing_phone,
        'email' => $this->billing_email,
        'address1' => $this->billing_address1,
        'address2' => $this->billing_address2,
        'type' => 'billing',
        'country' => $this->billing_country,
        'country_iso' => Country::where('name', $this->billing_country)->first()->iso_code ?? null,
        'county' => $this->billing_county,
        'county_iso' => County::where('name', $this->billing_county)->first()->iso_code ?? null,
        'city' => $this->billing_city,
        'is_default' => true,
        'zipcode' => $this->billing_zipcode,
      ];

      $billing = $this->findOrCreateAddress($billingAddressData);

      if (!$this->is_identic) {
        $shippingAddressData = [
          'account_id' => $account->id,
          'first_name' => $this->shipping_first,
          'last_name' => $this->shipping_last,
          'phone' => $this->shipping_phone,
          'email' => $this->shipping_email,
          'address1' => $this->shipping_address1,
          'address2' => $this->shipping_address2,
          'type' => 'shipping',
          'country' => $this->shipping_country,
          'country_iso' => Country::where('name', $this->shipping_country)->first()->iso_code ?? null,
          'county' => $this->shipping_county,
          'county_iso' => County::where('name', $this->shipping_county)->first()->iso_code ?? null,
          'city' => $this->shipping_city,
          'is_default' => true,
          'zipcode' => $this->shipping_zipcode,
        ];

        $shipping = $this->findOrCreateAddress($shippingAddressData);
      } else {
        $shipping = $this->findOrCreateAddress(array_merge($billingAddressData, ['type' => 'shipping']));
      }

      $baseName = 'Order';
      $lastOrder = Order::latest('id')->first();
      $orderNumber = $lastOrder ? ((int)str_replace("{$baseName}_", '', $lastOrder->name) + 1) : 1;
      $uniqueName = "{$baseName}_" . str_pad($orderNumber, 2, '0', STR_PAD_LEFT);
      $status = $this->payment['type'] != 'card' ? app('global_statuses')['order_processing'] : app('global_statuses')['order_check_payment'];

      $prefix = app('global_order_prefix') . now(config('app.timezone'))->format('Ymd');

      $lastTodayOrder = Order::where('order_number', 'LIKE', "{$prefix}%")
        ->latest('order_number')
        ->first();

      if ($lastTodayOrder) {
        $lastNumber = (int)substr($lastTodayOrder->order_number, -3);
        $today = $lastNumber + 1;
      } else {
        $today = 1;
      }

      $uniqueorderNumber = $prefix . str_pad($today, 3, '0', STR_PAD_LEFT);
      $this->orderNumber = $uniqueorderNumber;
      $orderdata = [
        'name' => $uniqueName,
        'session_id' => $this->session_id,
        'account_id' => $account->id,
        'billing_id' => $billing->id,
        'shipping_id' => $shipping->id,
        'cart_id' => $this->cart->id,
        'order_number' => $this->orderNumber,
        'quantity_amount' => $this->cart->quantity_amount,
        'sum_amount' => $this->cart->sum_amount,
        'final_amount' => ($this->cart->sum_amount + app('global_delivery_price') - $this->cart->voucher_value - $this->cart->promotion_value),
        'delivery_price' => app('global_delivery_price'),
        'delivery_price_vat' => app()->bound('global_delivery_price_vat') ? app('global_delivery_price_vat') : 19,
        'voucher_value' => $this->cart->voucher_value ?? 0,
        'promotion_value' => $this->cart->promotion_value ?? 0,
        'currency_id' => $this->cart->currency_id,
        'status_id' => $status,
        'payment_id' => $this->payment['id'],
        'voucher_id' => $this->cart->voucher_id ?? null,
        'subscribe' => $this->subscribe,
      ];

      $order = Order::updateOrCreate(
        ['cart_id' => $orderdata['cart_id']],
        $orderdata
      );

      //TELEGRAM NOTIFICATION

          try {
              $telegramEnabled = app('global_telegram_notification') ?? 'false';
              $botToken = app('global_telegram_bot_api') ?? null;
              $channelId = app('global_telegram_channel') ?? null;
              
              if (($telegramEnabled === '1' || $telegramEnabled === 'true') && $botToken && $channelId) {
                  $order->load(['account', 'shipping', 'currency', 'payment']);
                  \Illuminate\Support\Facades\Notification::route('telegram', $channelId)
                      ->notify(new \App\Notifications\TelegramOrderNotification($order));
              }
          } catch (\Throwable $th) {
              \Log::error('Telegram notification error: ' . $th->getMessage());
          }
          /////////////////////


      foreach ($order->orders as $item) {
        $item->delete();
      }

      $productsToUpdate = [];
      $orderItemsToInsert = [];

      foreach ($this->cart->cartItems as $item) {
        if ($this->payment['type'] != 'card') {
          $productsToUpdate[$item->product_id] = ($productsToUpdate[$item->product_id] ?? 0) + $item->quantity;
        }

        $orderItemsToInsert[] = [
          'order_id' => $order->id,
          'product_id' => $item->product_id,
          'price' => $item->price,
          'quantity' => $item->quantity,
          'vat' => $item->vat,
          'created_at' => now(config('app.timezone')),
          'updated_at' => now(config('app.timezone'))
        ];
      }

      if (!empty($productsToUpdate)) {
        $cases = [];
        $ids = [];
        $bindings = [];

        foreach ($productsToUpdate as $id => $quantity) {
          $cases[] = "WHEN id = ? THEN quantity - ?";
          $bindings[] = $id;
          $bindings[] = $quantity;
          $ids[] = $id;
        }

        $cases = implode(" ", $cases);
        $ids = implode(",", $ids);

        DB::update("UPDATE products SET quantity = CASE $cases END, updated_at = ? WHERE id IN ($ids)", array_merge($bindings, [now(config('app.timezone'))]));
      }

      if (!empty($orderItemsToInsert)) {
        Order_Item::insert($orderItemsToInsert);
      }

      if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
        Cache::forget('cached_products');
      }

      if ($this->payment['type'] != 'card') {
        $this->cart->update(['order_id' => $order->id, 'status_id' => app('global_statuses')['cart_closed']]);
        $this->step = 3;
        $this->new_order = $order->load([
          'orders.product' => function ($query) {
            $query->select('id', 'name', 'seo_id')
              ->with([
                'media' => function ($query) {
                  $query->select('path', 'name', 'type')->where('type', 'min');
                },
                'product_prices' => function ($query) {
                  $query->select('product_id', 'value', 'discount', 'value_no_discount');
                },
              ]);
          },
          'account' => function ($query) {
            $query->select('id', 'name', 'phone', 'email', 'company_name', 'registration_code', 'registration_number', 'bank_name', 'account');
          },
          'billing' => function ($query) {
            $query->select('id', 'account_id', 'first_name', 'last_name', 'phone', 'email', 'address1', 'address2', 'type', 'country', 'county', 'city', 'zipcode');
          },
          'shipping' => function ($query) {
            $query->select('id', 'account_id', 'first_name', 'last_name', 'phone', 'email', 'address1', 'address2', 'type', 'country', 'county', 'city', 'zipcode');
          },
          'currency' => function ($query) {
            $query->select('id', 'name');
          },
        ]);

        $this->emit('orderprocess');

        try {
          Mail::to($order->account->email)->send(new ConfirmationOrder($order));
        } catch (\Throwable $th) {
          return;
        }
        $this->dispatchBrowserEvent('goup');
      } else {
        $this->cart->update(['order_id' => $order->id, 'status_id' => app('global_statuses')['cart_check_payment']]);
        Stripe::setApiKey(app('global_stripe_key'));

        $session = Session::create([
          'line_items' => [
            [
              'price_data' => [
                'currency' => $order->currency->name,
                'product_data' => ['name' => $order->order_number],
                'unit_amount' => $order->final_amount * 100,
              ],
              'quantity' => 1,
            ],
          ],
          'mode' => 'payment',
          'locale' => 'ro',
          'customer_email' => $order->account->email,
          'success_url' => route('payment_success', [], true) . "?session_id={$this->session_id}",
          'cancel_url' => route('payment_cancel', [], true) . "?session_id={$this->session_id}",
        ]);

        $this->orderNumber = $order->order_number;
        return redirect()->to($session->url);
      }
    }
  }
}
