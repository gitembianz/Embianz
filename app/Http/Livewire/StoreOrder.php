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
use App\Models\Cart_Item;
use App\Models\Order_Item;
use App\Models\UserSessions;
use Stripe\Checkout\Session;
use App\Mail\ConfirmationOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class StoreOrder extends Component
{
  public $step = 1;
  public $is_account = null;
  public $back = false;
  public $terms = false;
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
  public $countries;
  public $billingCounties = [];
  public $shippingCounties = [];
  public $jbillingCounties = [];
  public $jshippingCounties = [];


  // individual declaration
  public $individual = true;
  public $individual_identic = true;
  public $individual_billing_first;
  public $individual_billing_last;
  public $individual_billing_phone;
  public $individual_billing_email;
  public $individual_billing_address1;
  public $individual_billing_address2;
  public $individual_billing_country;
  public $individual_billing_county;
  public $individual_billing_city;
  public $individual_billing_zipcode;
  public $individual_shipping_first;
  public $individual_shipping_last;
  public $individual_shipping_phone;
  public $individual_shipping_email;
  public $individual_shipping_address1;
  public $individual_shipping_address2;
  public $individual_shipping_country;
  public $individual_shipping_county;
  public $individual_shipping_city;
  public $individual_shipping_zipcode;

  // Juridic declaration
  public $juridic = false;
  public $juridic_identic = true;
  public $juridic_billing_first;
  public $juridic_billing_last;
  public $juridic_billing_phone;
  public $juridic_billing_email;
  public $juridic_billing_company_name;
  public $juridic_billing_registration_code;
  public $juridic_billing_registration_number;
  public $juridic_billing_bank;
  public $juridic_billing_account;
  public $juridic_billing_address1;
  public $juridic_billing_address2;
  public $juridic_billing_country;
  public $juridic_billing_county;
  public $juridic_billing_city;
  public $juridic_billing_zipcode;
  public $juridic_shipping_first;
  public $juridic_shipping_last;
  public $juridic_shipping_phone;
  public $juridic_shipping_email;
  public $juridic_shipping_address1;
  public $juridic_shipping_address2;
  public $juridic_shipping_country;
  public $juridic_shipping_county;
  public $juridic_shipping_city;
  public $juridic_shipping_zipcode;

  public $rtc = false;
  public $crd = false;
  public $invoice = false;
  public $validatequantity = true;
  public $payment;
  protected $listeners = [
    'nocard' => 'mount',
    'cartUpdated' => 'mount',
    'isdisabled' => 'checkIsDisabled',
    'timmerexpired' => 'checkpromotions'
  ];

  public function updatedIndividualBillingCountry()
  {
    $this->individual_billing_county = null;
    $this->billingCounties = $this->getBillingCounties();
  }
  public function updatedJuridicBillingCountry()
  {
    $this->juridic_billing_county = null;
    $this->jbillingCounties = $this->getJBillingCounties();
  }
  public function updatedIndividualShippingCountry()
  {
    $this->individual_shipping_county = null;
    $this->shippingCounties = $this->getShippingCounties();
  }
  public function updatedJuridicShippingCountry()
  {
    $this->juridic_shipping_county = null;
    $this->jshippingCounties = $this->getJShippingCounties();
  }

  public function getBillingCounties()
  {
    $activeCountries = $this->countries;
    $selectedCountry = $activeCountries->firstWhere('name', $this->individual_billing_country);

    $counties = isset($selectedCountry) ? collect($selectedCountry['counties'])->toArray() : [];

    if (count($counties) > 0 && !$this->individual_billing_county) {
      $this->individual_billing_county = $counties[0]['name'];
    }

    return $counties;
  }

  public function getJBillingCounties()
  {
    $activeCountries = $this->countries;
    $selectedCountry = $activeCountries->firstWhere('name', $this->juridic_billing_country);
    $counties = $selectedCountry['counties'] ?? [];

    if (count($counties) > 0 && !$this->juridic_billing_county) {
      $this->juridic_billing_county = $counties[0]['name'];
    }
    return $counties;
  }
  public function getShippingCounties()
  {
    $activeCountries = $this->countries;
    $selectedCountry = $activeCountries->firstWhere('name', $this->individual_shipping_country);
    $counties = $selectedCountry['counties'] ?? [];

    if (count($counties) > 0 && !$this->individual_shipping_county) {
      $this->individual_shipping_county = $counties[0]['name'];
    }

    return $counties;
  }
  public function getJShippingCounties()
  {
    $activeCountries = $this->countries;
    $selectedCountry = $activeCountries->firstWhere('name', $this->juridic_shipping_country);
    $counties = $selectedCountry['counties'] ?? [];

    if (count($counties) > 0 && !$this->juridic_shipping_county) {
      $this->juridic_shipping_county = $counties[0]['name'];
    }
    return $counties;
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
      if ($this->cart->status_id === app('global_cart_new')) {

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

  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount', 'currency_id', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value', 'status_id', 'promotion_value')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
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

  public function showindividual()
  {
    $this->individual = true;
    $this->juridic = false;
  }
  public function showjuridic()
  {
    $this->individual = false;
    $this->juridic = true;
  }

  public function previous()
  {
    $this->step--;
    $this->resetErrorBag();
  }


  public function next()
  {
    if (!$this->cart->cartItems || !$this->cart) {
      $this->back = true;
    } else {
      cookie()->queue(cookie()->forget('accountId'));
      $this->resetErrorBag();
      $this->validateData();
      $this->step++;
      if ($this->individual && $this->individual_identic) {
        $this->individual_shipping_first = $this->individual_billing_first;
        $this->individual_shipping_last = $this->individual_billing_last;
        $this->individual_shipping_phone = $this->individual_billing_phone;
        $this->individual_shipping_email = $this->individual_billing_email;
        $this->individual_shipping_address1 = $this->individual_billing_address1;
        $this->individual_shipping_address2 = $this->individual_billing_address2;
        $this->individual_shipping_country = $this->individual_billing_country;
        $this->individual_shipping_county = $this->individual_billing_county;
        $this->individual_shipping_city = $this->individual_billing_city;
        $this->individual_shipping_zipcode = $this->individual_billing_zipcode;
      }
      if ($this->juridic && $this->juridic_identic) {
        $this->juridic_shipping_first = $this->juridic_billing_first;
        $this->juridic_shipping_last = $this->juridic_billing_last;
        $this->juridic_shipping_phone = $this->juridic_billing_phone;
        $this->juridic_shipping_email = $this->juridic_billing_email;
        $this->juridic_shipping_address1 = $this->juridic_billing_address1;
        $this->juridic_shipping_address2 = $this->juridic_billing_address2;
        $this->juridic_shipping_country = $this->juridic_billing_country;
        $this->juridic_shipping_county = $this->juridic_billing_county;
        $this->juridic_shipping_city = $this->juridic_billing_city;
        $this->juridic_shipping_zipcode = $this->juridic_billing_zipcode;
      }
      $this->cart->update([
        'status_id' => app('global_cart_checkoutdetails')
      ]);
      $this->dispatchBrowserEvent('goup');
    }
  }

  public function validateData()
  {

    if ($this->individual) {
      $rules = [
        'individual_billing_first' => [
          'required',
          'min:2',
          'max:100',
        ],
        'individual_billing_last' => [
          'required',
          'min:2',
          'max:100',

        ],
        'individual_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
        'individual_billing_email' => 'required|email',
        'individual_billing_address1' => [
          'required',
          'min:1',
          'max:100',
        ],
        'individual_billing_city' =>
        [
          'required',
          'min:1',
          'max:40',
        ],
      ];

      if (!$this->individual_identic) {
        $shippingRules = [
          'individual_shipping_first' => [
            'required',
            'min:2',
            'max:100',
          ],
          'individual_shipping_last' => [
            'required',
            'min:2',
            'max:100',
          ],
          'individual_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
          'individual_shipping_email' => 'required|email',
          'individual_shipping_address1' =>
          [
            'required',
            'min:1',
            'max:100',
          ],
          'individual_shipping_city' =>
          [
            'required',
            'min:1',
            'max:40',
          ],
        ];
        $rules = array_merge($rules, $shippingRules);
      }

      $this->validate($rules);
    }
    if ($this->juridic) {
      $rules = [
        'juridic_billing_first' => [
          'required',
          'min:2',
          'max:100',
        ],
        'juridic_billing_last' => [
          'required',
          'min:2',
          'max:100',
        ],
        'juridic_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
        'juridic_billing_email' => 'required|email',
        'juridic_billing_company_name' => [
          'required',
          'min:1',
          'max:100',
        ],
        'juridic_billing_registration_code' => [
          'required',
          'min:1',
          'max:100',
        ],
        'juridic_billing_registration_number' => [
          'required',
          'min:1',
          'max:100',
        ],
        'juridic_billing_address1' => [
          'required',
          'min:1',
          'max:100',
        ],
        'juridic_billing_city' =>
        [
          'required',
          'min:1',
          'max:40',
        ],
      ];
      if (!$this->juridic_identic) {
        $shippingRules = [
          'juridic_shipping_first' => [
            'required',
            'min:2',
            'max:100',
          ],
          'juridic_shipping_last' => [
            'required',
            'min:2',
            'max:100',
          ],
          'juridic_shipping_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
          'juridic_shipping_email' => 'required|email',
          'juridic_shipping_address1' => [
            'required',
            'min:1',
            'max:100',
          ],
          'juridic_shipping_city' => [
            'required',
            'min:1',
            'max:40',
          ],
        ];
        $rules = array_merge($rules, $shippingRules);
      }
      $this->validate($rules);
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

  public function mount()
  {

    $this->modification = false;
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();
    $this->country = app()->make('active_countries')->where('name',  app('global_default_country'))->first() ?? 'n/a';
    $this->countries = app()->make('active_countries') ?? null;



    if (app()->has("global_check_terms_order") && app('global_check_terms_order') == 'true') {
      $this->terms = true;
    }
    if (session()->has('paymentcancel')) {
      $this->payment_cancel = true;
      $this->step = 2;
      $message = app('label_order_payment_cancel_text') ?? "";
      $this->emit('alert__modal', ['message' => $message]);
      session()->forget('paymentcancel');
    }


    if (session()->has('paymentsucces')) {
      if ($this->cart->voucher && $this->cart->voucher->single_use) {
        Voucher::where('id', $this->cart->voucher_id)->update([
          'status_id' => app('global_voucher_closed')
        ]);
      }
      $this->cart->update([
        'status_id' => app('global_cart_closed')
      ]);
      $order = Order::where('session_id', $this->session_id)->where('status_id', app('global_order_check_payment'))->first();
      $order->status_id = app('global_order_processing');
      $this->orderNumber = $order->order_number;
      $order->save();
      $this->new_order = $order;
      foreach ($order->orders as $item) {
        $item->product->quantity -= $item->quantity;
        $item->product->save();
      }
      $this->step = 3;
      session()->forget('paymentsucces');
      try {
        Mail::to($order->account->email)->send(new ConfirmationOrder($order));
      } catch (\Throwable $th) {
        return;
      }
    }

    if (!$this->cart || !$this->cart->cartItems) {
      $this->back = true;
    }
    if ($this->country != 'n/a') {

      $this->individual_billing_country = $this->country->name;
      $this->individual_shipping_country = $this->country->name;
      $this->juridic_billing_country = $this->country->name;
      $this->juridic_shipping_country = $this->country->name;
    } else {
      $this->individual_billing_country = 'Romania';
      $this->individual_shipping_country = 'Romania';
      $this->juridic_billing_country = 'Romania';
      $this->juridic_shipping_country = 'Romania';
    }



    if (request()->cookie('accountId')) {
      $this->is_account = request()->cookie('accountId');
    }
    if ($this->is_account != null) {
      $account = Account::with('addresses', 'orders')->find($this->is_account) ?? null;
      if (!$account) {
        unset($_COOKIE['accountId']);
        $this->is_account = null;
      } else {
        cookie()->queue(cookie()->forget('accountId'));

        if ($account->type == 'individual') {
          $this->individual = true;
          $this->juridic = false;

          $this->individual_billing_first = $account->first_name;
          $this->individual_billing_last = $account->last_name;
          $this->individual_billing_phone = $account->phone;
          $this->individual_billing_email = $account->email;
          $this->individual_billing_address1 = $account->addresses->where('type', 'billing')->first()->address1;
          $this->individual_billing_address2 = $account->addresses->where('type', 'billing')->first()->address2;
          $this->individual_billing_country = $account->addresses->where('type', 'billing')->first()->country;

          $this->individual_billing_city = $account->addresses->where('type', 'billing')->first()->city;
          $this->individual_billing_zipcode = $account->addresses->where('type', 'billing')->first()->zipcode;
          $this->individual_shipping_first = $account->addresses->where('type', 'shipping')->first()->first_name;
          $this->individual_shipping_last = $account->addresses->where('type', 'shipping')->first()->last_name;
          $this->individual_shipping_phone = $account->addresses->where('type', 'shipping')->first()->phone;
          $this->individual_shipping_email = $account->addresses->where('type', 'shipping')->first()->email;
          $this->individual_shipping_address1 = $account->addresses->where('type', 'shipping')->first()->address1;
          $this->individual_shipping_address2 = $account->addresses->where('type', 'shipping')->first()->address2;
          $this->individual_shipping_country = $account->addresses->where('type', 'shipping')->first()->country;


          $this->individual_shipping_city = $account->addresses->where('type', 'shipping')->first()->city;
          $this->individual_shipping_zipcode = $account->addresses->where('type', 'shipping')->first()->zipcode;
          $this->individual_shipping_county = $account->addresses->where('type', 'shipping')->first()->county;
          $this->individual_billing_county = $account->addresses->where('type', 'billing')->first()->county;
        } else {
          $this->juridic = true;
          $this->individual = false;

          $this->juridic_billing_first = $account->first_name;
          $this->juridic_billing_last = $account->last_name;
          $this->juridic_billing_phone = $account->phone;
          $this->juridic_billing_email = $account->email;
          $this->juridic_billing_company_name = $account->company_name;
          $this->juridic_billing_registration_code = $account->registration_code;
          $this->juridic_billing_registration_number = $account->registration_number;
          $this->juridic_billing_bank = $account->bank_name;
          $this->juridic_billing_account = $account->account;
          $this->juridic_billing_address1 = $account->addresses->where('type', 'billing')->first()->address1;
          $this->juridic_billing_address2 = $account->addresses->where('type', 'billing')->first()->address2;
          $this->juridic_billing_country = $account->addresses->where('type', 'billing')->first()->country;
          $this->juridic_billing_city = $account->addresses->where('type', 'billing')->first()->city;
          $this->juridic_billing_zipcode = $account->addresses->where('type', 'billing')->first()->zipcode;
          $this->juridic_shipping_first = $account->addresses->where('type', 'shipping')->first()->first_name;
          $this->juridic_shipping_last = $account->addresses->where('type', 'shipping')->first()->last_name;
          $this->juridic_shipping_phone = $account->addresses->where('type', 'shipping')->first()->phone;
          $this->juridic_shipping_email = $account->addresses->where('type', 'shipping')->first()->email;
          $this->juridic_shipping_address1 = $account->addresses->where('type', 'shipping')->first()->address1;
          $this->juridic_shipping_address2 = $account->addresses->where('type', 'shipping')->first()->address2;
          $this->juridic_shipping_country = $account->addresses->where('type', 'shipping')->first()->country;
          $this->juridic_shipping_city = $account->addresses->where('type', 'shipping')->first()->city;
          $this->juridic_shipping_zipcode = $account->addresses->where('type', 'shipping')->first()->zipcode;
          $this->juridic_shipping_county = $account->addresses->where('type', 'shipping')->first()->county;
          $this->juridic_billing_county = $account->addresses->where('type', 'billing')->first()->county;
        }
      }
    }
    $this->billingCounties = $this->getBillingCounties();
    $this->shippingCounties = $this->getShippingCounties();
    $this->jbillingCounties = $this->getJBillingCounties();
    $this->jshippingCounties = $this->getJShippingCounties();
    $this->cash = app('global_cash');
    $this->card = app('global_card_stripe');
    $this->ordin = app('global_ordin');
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
        'status_id' => app('global_cart_checkoutdetails')
      ]);
      $this->validatequantity = true;
    }
  }

  public function render()
  {
    $data = [
      'cart' => $this->cart
    ];
    return view('livewire.store-order', $data);
  }

  public function confirm()
  {
    if (!$this->terms) {
      $this->errorterms = true;
      $this->dispatchBrowserEvent('terms__error');
      return;
    }

    if ($this->cart->cartItems && ($this->cart->status_id == app('global_cart_checkoutdetails'))) {
      if ($this->cart->voucher) {
        $voucher = $this->cart->voucher;
        $currentDate = now()->format('Y-m-d');
        if ($voucher->status_id == app('global_voucher_closed') || $voucher->start_date > $currentDate || $voucher->end_date < $currentDate) {
          $message = app('label_order_error_voucher') ?? "";
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          $this->cart->update([
            'final_amount' => ($this->cart->sum_amount + app('global_delivery_price')),
            'voucher_id' => null,
            'voucher_value' => 0,
            'updated_at' => now(),
          ]);
          return;
        }
      }

      foreach ($this->cart->cartItems as $item) {
        $product = $item->product;
        if ($item->quantity > $product->quantity && !$product->preorder) {
          $this->validatequantity = false;
          $message = app('label_order_error_quantity') ?? "";
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          return;
        }
      }

      foreach ($this->cart->cartItems as $item) {
        $product = $item->product;
        $currentDate = now()->format('Y-m-d');
        if (!$product->active || $product->start_date > $currentDate || $product->end_date < $currentDate) {
          $message = app('label_order_error_active') ?? "";
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          return;
        }
      }
    } else {
      $this->emit('cartUpdated');
      $this->validatequantity = false;
      $message = app('label_order_error_cart') ?? "";
      $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
      return;
    }

    if ($this->validatequantity) {
      $accountData = [
        'phone' => $this->individual ? $this->individual_billing_phone : $this->juridic_billing_phone,
        'email' => $this->individual ? $this->individual_billing_email : $this->juridic_billing_email,
      ];

      if ($this->individual) {
        $accountData = array_merge($accountData, [
          'name' => $this->individual_billing_first . " " . $this->individual_billing_last,
          'type' => 'individual',
          'first_name' => $this->individual_billing_first,
          'last_name' => $this->individual_billing_last,
        ]);
      } else {
        $accountData = array_merge($accountData, [
          'name' => $this->juridic_billing_first . " " . $this->juridic_billing_last . ", " . $this->juridic_billing_company_name,
          'type' => 'juridic',
          'first_name' => $this->juridic_billing_first,
          'last_name' => $this->juridic_billing_last,
          'company_name' => $this->juridic_billing_company_name,
          'registration_code' => $this->juridic_billing_registration_code,
          'registration_number' => $this->juridic_billing_registration_number,
          'bank_name' => $this->juridic_billing_bank,
          'account' => $this->juridic_billing_account,
        ]);
      }

      $account = Account::create($accountData);
      cookie()->queue(cookie()->make('accountId', $account->id, 60 * 24 * 30));

      $billingAddressData = [
        'account_id' => $account->id,
        'first_name' => $this->individual ? $this->individual_billing_first : $this->juridic_billing_first,
        'last_name' => $this->individual ? $this->individual_billing_last : $this->juridic_billing_last,
        'phone' => $this->individual ? $this->individual_billing_phone : $this->juridic_billing_phone,
        'email' => $this->individual ? $this->individual_billing_email : $this->juridic_billing_email,
        'address1' => $this->individual ? $this->individual_billing_address1 : $this->juridic_billing_address1,
        'address2' => $this->individual ? $this->individual_billing_address2 : $this->juridic_billing_address2,
        'type' => 'billing',
        'country' => $this->individual ? $this->individual_billing_country : $this->juridic_billing_country,
        'country_iso' => Country::where('name', $this->individual ? $this->individual_billing_country : $this->juridic_billing_country)->first()->iso_code,
        'county' => $this->individual ? $this->individual_billing_county : $this->juridic_billing_county,
        'county_iso' => County::where('name', $this->individual ? $this->individual_billing_county : $this->juridic_billing_county)->first()->iso_code ?? null,
        'city' => $this->individual ? $this->individual_billing_city : $this->juridic_billing_city,
        'zipcode' => $this->individual ? $this->individual_billing_zipcode : $this->juridic_billing_zipcode,
      ];

      Address::create($billingAddressData);

      if (!$this->individual_identic || !$this->juridic_identic) {
        $shippingAddressData = [
          'account_id' => $account->id,
          'first_name' => $this->individual ? $this->individual_shipping_first : $this->juridic_shipping_first,
          'last_name' => $this->individual ? $this->individual_shipping_last : $this->juridic_shipping_last,
          'phone' => $this->individual ? $this->individual_shipping_phone : $this->juridic_shipping_phone,
          'email' => $this->individual ? $this->individual_shipping_email : $this->juridic_shipping_email,
          'address1' => $this->individual ? $this->individual_shipping_address1 : $this->juridic_shipping_address1,
          'address2' => $this->individual ? $this->individual_shipping_address2 : $this->juridic_shipping_address2,
          'type' => 'shipping',
          'country' => $this->individual ? $this->individual_shipping_country : $this->juridic_shipping_country,
          'country_iso' => Country::where('name', $this->individual ? $this->individual_shipping_country : $this->juridic_shipping_country)->first()->iso_code,
          'county' => $this->individual ? $this->individual_shipping_county : $this->juridic_shipping_county,
          'county_iso' => County::where('name', $this->individual ? $this->individual_shipping_county : $this->juridic_shipping_county)->first()->iso_code ?? null,
          'city' => $this->individual ? $this->individual_shipping_city : $this->juridic_shipping_city,
          'zipcode' => $this->individual ? $this->individual_shipping_zipcode : $this->juridic_shipping_zipcode,
        ];

        Address::create($shippingAddressData);
      } else {
        Address::create(array_merge($billingAddressData, ['type' => 'shipping']));
      }

      $baseName = 'Order';
      $lastOrder = Order::latest('id')->first();
      $orderNumber = $lastOrder ? ((int)str_replace("{$baseName}_", '', $lastOrder->name) + 1) : 1;
      $uniqueName = "{$baseName}_" . str_pad($orderNumber, 2, '0', STR_PAD_LEFT);
      $status = $this->payment['type'] != 'card' ? app('global_order_processing') : app('global_order_check_payment');

      $prefix = app('global_order_prefix') . now()->format('Ymd');

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

      $order = Order::create([
        'name' => $uniqueName,
        'session_id' => $this->session_id,
        'account_id' => $account->id,
        'cart_id' => $this->cart->id,
        'order_number' => $this->orderNumber,
        'quantity_amount' => $this->cart->quantity_amount,
        'sum_amount' => $this->cart->sum_amount,
        'final_amount' => ($this->cart->sum_amount + app('global_delivery_price') - $this->cart->voucher_value - $this->cart->promotion_value),
        'delivery_price' => app('global_delivery_price'),
        'voucher_value' => $this->cart->voucher_value ?? 0,
        'promotion_value' => $this->cart->promotion_value ?? 0,
        'currency_id' => $this->cart->currency_id,
        'status_id' => $status,
        'payment_id' => $this->payment['id'],
        'voucher_id' => $this->cart->voucher_id
      ]);


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
          'created_at' => now(),
          'updated_at' => now()
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

        DB::update("UPDATE products SET quantity = CASE $cases END, updated_at = ? WHERE id IN ($ids)", array_merge($bindings, [now()]));
      }

      if (!empty($orderItemsToInsert)) {
        Order_Item::insert($orderItemsToInsert);
      }


      if ($this->cart->voucher && $this->cart->voucher->single_use) {
        Voucher::where('id', $this->cart->voucher_id)->update(['status_id' => app('global_voucher_closed')]);
      }

      if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
        Cache::forget('cached_products');
      }

      if ($this->payment['type'] != 'card') {
        $this->cart->update(['order_id' => $order->id, 'status_id' => app('global_cart_closed')]);
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
            $query->select('id', 'name', 'phone', 'email', 'company_name', 'registration_code', 'registration_number', 'bank_name', 'account')
              ->with([
                'addresses' => function ($query) {
                  $query->select('id', 'account_id', 'first_name', 'last_name', 'phone', 'email', 'address1', 'address2', 'type', 'country', 'county', 'city', 'zipcode');
                },
              ]);
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
        $this->cart->update(['order_id' => $order->id, 'status_id' => app('global_cart_check_payment')]);
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