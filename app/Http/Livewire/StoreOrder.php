<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Account;
use App\Models\Address;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\Order_Item;

class StoreOrder extends Component
{
  public $step = 1;
  public $is_account;
  public $back = false;
  public $terms = false;
  public $errorterms = false;
  public $session_id;
  public $cash;
  public $card;
  public $ordin;
  public $orderNumber;
  protected $debug = true;

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

  public $rtc = true;
  public $crd = false;
  public $invoice = false;
  public $validatequantity = true;
  public $payment;
  protected $listeners = [
    'nocard' => 'mount',
    'cartUpdated' => 'mount',
  ];

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

  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount', 'currency_id', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value', 'status_id')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->with(['voucher' => function ($query) {
        $query->select('code', 'id', 'percent', 'value');
      }])
      ->latest()
      ->first() ?? null;
  }

  public function getCartItemsProperty()
  {
    if ($this->cart) {
      return Cart_Item::select('id', 'quantity', 'price', 'product_id')
        ->where('cart_id', $this->cart->id)
        ->with([
          'product' => function ($query) {
            $query->select('id', 'name', 'seo_id', 'quantity')->with([
              'media' => function ($query) {
                $query->select('path', 'name')->where('type', 'min');
              },
              'product_prices' => function ($query) {
                $query->select('product_id', 'value', 'pricelist_id')
                  ->with(['pricelist' => function ($query) {
                    $query->select('id', 'currency_id')->with('currency:id,name');
                  }]);
              }
            ]);
          }
        ])->get() ?? collect();
    } else {
      return collect();
    }
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

  public function resetForm()
  {
    $this->reset([
      'individual_billing_first', 'individual_billing_last', 'individual_billing_phone', 'individual_billing_email',
      'individual_billing_address1', 'individual_billing_address2', 'individual_billing_county',
      'individual_billing_city', 'individual_billing_zipcode',
      'individual_shipping_first', 'individual_shipping_last', 'individual_shipping_phone', 'individual_shipping_email',
      'individual_shipping_address1', 'individual_shipping_address2', 'individual_shipping_county',
      'individual_shipping_city', 'individual_shipping_zipcode',
      'juridic_billing_first', 'juridic_billing_last', 'juridic_billing_phone', 'juridic_billing_email',
      'juridic_billing_company_name', 'juridic_billing_registration_code', 'juridic_billing_registration_number',
      'juridic_billing_bank', 'juridic_billing_account',
      'juridic_billing_address1', 'juridic_billing_address2', 'juridic_billing_county',
      'juridic_billing_city', 'juridic_billing_zipcode',
      'juridic_shipping_first', 'juridic_shipping_last', 'juridic_shipping_phone', 'juridic_shipping_email',
      'juridic_shipping_address1', 'juridic_shipping_address2', 'juridic_shipping_county',
      'juridic_shipping_city', 'juridic_shipping_zipcode'
    ]);
  }

  public function next()
  {
    if ($this->cartItems->isEmpty() || !$this->cart) {
      $this->back = true;
    } else {
      $this->resetErrorBag();
      $this->validateData();
      $this->step++;
      if ($this->individual_identic) {
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
      if ($this->juridic_identic) {
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
      $this->dispatchBrowserEvent('next_step');
    }
  }

  public function validateData()
  {

    if ($this->individual) {
      $rules = [
        'individual_billing_first' => [
          'required',
          'min:2',
          'max:20',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z\s]*$/'
        ],
        'individual_billing_last' => [
          'required',
          'min:2',
          'max:20',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z\s]*$/'
        ],
        'individual_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
        'individual_billing_email' => 'required|email',
        'individual_billing_address1' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
        'individual_billing_county' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
        'individual_billing_city' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
        'individual_billing_zipcode' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
      ];

      if (!$this->individual_identic) {
        $shippingRules = [
          'individual_shipping_first' => [
            'required',
            'min:2',
            'max:20',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z\s]*$/'
          ],
          'individual_shipping_last' => [
            'required',
            'min:2',
            'max:20',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z\s]*$/'
          ],
          'individual_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
          'individual_shipping_email' => 'required|email',
          'individual_shipping_address1' =>
          [
            'required',
            'min:1',
            'max:100',
            'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
          ],
          'individual_shipping_county' =>
          [
            'required',
            'min:1',
            'max:100',
            'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
          ],
          'individual_shipping_city' =>
          [
            'required',
            'min:1',
            'max:100',
            'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
          ],
          'individual_shipping_zipcode' =>
          [
            'required',
            'min:1',
            'max:100',
            'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
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
          'max:20',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z\s]*$/'
        ],
        'juridic_billing_last' => [
          'required',
          'min:2',
          'max:20',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z\s]*$/'
        ],
        'juridic_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}$/',
        'juridic_billing_email' => 'required|email',
        'juridic_billing_company_name' => [
          'required',
          'regex:/^[a-zA-Z0-9]*$/',
          'regex:/^[^\s]+(\s+[^\s]+)*$/'
        ],
        'juridic_billing_registration_code' => [
          'required',
          'regex:/^[a-zA-Z0-9]*$/',
          'regex:/^[^\s]+(\s+[^\s]+)*$/'
        ],
        'juridic_billing_registration_number' => [
          'required',
          'regex:/^[0-9]*$/',
          'regex:/^[^\s]+(\s+[^\s]+)*$/'
        ],
        'juridic_billing_address1' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
        'juridic_billing_county' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
        'juridic_billing_city' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
        'juridic_billing_zipcode' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ],
      ];
      if (!$this->juridic_identic) {
        $shippingRules = [
          'juridic_shipping_first' => [
            'required',
            'min:2',
            'max:20',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z\s]*$/'
          ],
          'juridic_shipping_last' => [
            'required',
            'min:2',
            'max:20',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z\s]*$/'
          ],
          'juridic_shipping_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}$/',
          'juridic_shipping_email' => 'required|email',
          'juridic_shipping_address1' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
          ],
          'juridic_shipping_county' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
          ],
          'juridic_shipping_city' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
          ],
          'juridic_shipping_zipcode' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
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
      $this->rtc = true;
      $this->crd = true;
      $this->invoice = false;
    }
    if ($item == 'crd') {
      $this->payment = $this->card;
      $this->crd = true;
      $this->rtc = false;
      $this->invoice = false;
    }
    if ($item == 'invoice') {
      $this->payment = $this->ordin;
      $this->rtc = false;
      $this->crd = false;
      $this->invoice = true;
    }
  }

  public function mount()
  {
    $this->session_id = $this->getSessionId();

    if ($this->cartItems->isEmpty() || !$this->cart) {
      $this->back = true;
    }
    if (array_key_exists('accountId', $_COOKIE)) {
      $this->is_account = $_COOKIE['accountId'];
    } else {
      $this->is_account = null;
    }
    if ($this->is_account != null) {
      $account = Account::with('addresses', 'orders')->find($this->is_account);
      if ($account->type == 'individual') {
        $this->individual = true;
        $this->individual_billing_first = $account->first_name;
        $this->individual_billing_last = $account->last_name;
        $this->individual_billing_phone = $account->phone;
        $this->individual_billing_email = $account->email;
        $this->individual_billing_address1 = $account->addresses->where('type', 'billing')->first()->address1;
        $this->individual_billing_address2 = $account->addresses->where('type', 'billing')->first()->address2;
        $this->individual_billing_country = $account->addresses->where('type', 'billing')->first()->country;
        $this->individual_billing_county = $account->addresses->where('type', 'billing')->first()->county;
        $this->individual_billing_city = $account->addresses->where('type', 'billing')->first()->city;
        $this->individual_billing_zipcode = $account->addresses->where('type', 'billing')->first()->zipcode;
        $this->individual_shipping_first = $account->addresses->where('type', 'shipping')->first()->first_name;
        $this->individual_shipping_last = $account->addresses->where('type', 'shipping')->first()->last_name;
        $this->individual_shipping_phone = $account->addresses->where('type', 'shipping')->first()->phone;
        $this->individual_shipping_email = $account->addresses->where('type', 'shipping')->first()->email;
        $this->individual_shipping_address1 = $account->addresses->where('type', 'shipping')->first()->address1;
        $this->individual_shipping_address2 = $account->addresses->where('type', 'shipping')->first()->address2;
        $this->individual_shipping_country = $account->addresses->where('type', 'shipping')->first()->country;
        $this->individual_shipping_county = $account->addresses->where('type', 'shipping')->first()->county;
        $this->individual_shipping_city = $account->addresses->where('type', 'shipping')->first()->city;
        $this->individual_shipping_zipcode = $account->addresses->where('type', 'shipping')->first()->zipcode;
      } else {
        $this->juridic = true;
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
        $this->juridic_billing_county = $account->addresses->where('type', 'billing')->first()->county;
        $this->juridic_billing_city = $account->addresses->where('type', 'billing')->first()->city;
        $this->juridic_billing_zipcode = $account->addresses->where('type', 'billing')->first()->zipcode;
        $this->juridic_shipping_first = $account->addresses->where('type', 'shipping')->first()->first_name;
        $this->juridic_shipping_last = $account->addresses->where('type', 'shipping')->first()->last_name;
        $this->juridic_shipping_phone = $account->addresses->where('type', 'shipping')->first()->phone;
        $this->juridic_shipping_email = $account->addresses->where('type', 'shipping')->first()->email;
        $this->juridic_shipping_address1 = $account->addresses->where('type', 'shipping')->first()->address1;
        $this->juridic_shipping_address2 = $account->addresses->where('type', 'shipping')->first()->address2;
        $this->juridic_shipping_country = $account->addresses->where('type', 'shipping')->first()->country;
        $this->juridic_shipping_county = $account->addresses->where('type', 'shipping')->first()->county;
        $this->juridic_shipping_city = $account->addresses->where('type', 'shipping')->first()->city;
        $this->juridic_shipping_zipcode = $account->addresses->where('type', 'shipping')->first()->zipcode;
      }
    }
    $this->cash = app('global_cash');
    $this->card = app('global_card_stripe');
    $this->ordin = app('global_ordin');
    $this->payment = $this->cash;

    if ($this->step == 2) {
      $this->cart->update([
        'status_id' => app('global_cart_checkoutdetails')
      ]);
      $this->validatequantity = true;
    }
    if ($this->is_account == null) {
      $this->individual_billing_country = app('global_default_country');
      $this->individual_shipping_country = app('global_default_country');
      $this->juridic_billing_country = app('global_default_country');
      $this->juridic_shipping_country = app('global_default_country');
    }
  }

  public function render()
  {
    if ($this->step == 2) {
      $data = [
        'cartItems' => $this->cartItems,
        'cart' => $this->cart
      ];
      return view('livewire.store-order', $data);
    } else {
      return view('livewire.store-order');
    }
  }


  public function confirm()
  {
    if (!$this->terms) {
      $this->errorterms = true;
      $this->dispatchBrowserEvent('terms__error');
      return;
    }

    if ($this->cartitems && ($this->cart->status_id == app('global_cart_checkoutdetails'))) {
      if ($this->cart->voucher) {
        if (($this->cart->voucher->first()->status_id == app('global_voucher_closed')) || ($this->cart->voucher->first()->start_date > now()) || ($this->cart->voucher->first()->end_date < now())) {
          $this->dispatchBrowserEvent('alert__modal');
          $this->cart->update([
            'final_amount' => ($this->cart->sum_amount + app('global_delivery_price')),
            'voucher_id' => null,
            'voucher_value' => 0,
            'updated_at' => now(),
          ]);
          return;
        }
      }
      foreach ($this->cartitems as $item) {
        if ($item->quantity > $item->product->quantity) {
          $this->validatequantity = false;
          $this->dispatchBrowserEvent('alert__modal');
          return;
        }
      }
    } else {
      $this->emit('cartUpdated');
      $this->validatequantity = false;
      $this->dispatchBrowserEvent('alert__modal');
      return;
    }
    if ($this->validatequantity) {

      if ($this->individual) {

        if ($this->is_account != null) {
          Account::where('id', $this->is_account)->update([
            'name' => $this->individual_billing_first . " " . $this->individual_billing_last,
            'type' => 'individual',
            'first_name' => $this->individual_billing_first,
            'last_name' => $this->individual_billing_last,
            'phone' => $this->individual_billing_phone,
            'email' => $this->individual_billing_email,
            'updated_at' => now(),
          ]);
          Address::where('account_id', $this->is_account)->where('type', 'billing')->update([
            'address1' => $this->individual_billing_address1,
            'address2' => $this->individual_billing_address2,
            'country' => $this->individual_billing_country,
            'county' => $this->individual_billing_county,
            'city' => $this->individual_billing_city,
            'zipcode' => $this->individual_billing_zipcode,
            'updated_at' => now(),
          ]);
          Address::where('account_id', $this->is_account)->where('type', 'shipping')->update([
            'first_name' => $this->individual_shipping_first,
            'last_name' => $this->individual_shipping_last,
            'phone' => $this->individual_shipping_phone,
            'email' => $this->individual_shipping_email,
            'address1' => $this->individual_shipping_address1,
            'address2' => $this->individual_shipping_address2,
            'country' => $this->individual_shipping_country,
            'county' => $this->individual_shipping_county,
            'city' => $this->individual_shipping_city,
            'zipcode' => $this->individual_shipping_zipcode,
            'updated_at' => now(),
          ]);
        } else {
          $account = Account::create([
            'name' => $this->individual_billing_first . " " . $this->individual_billing_last,
            'type' => 'individual',
            'first_name' => $this->individual_billing_first,
            'last_name' => $this->individual_billing_last,
            'phone' => $this->individual_billing_phone,
            'email' => $this->individual_billing_email
          ]);
          setcookie('accountId', $account->id, time() + 30 * 24 * 60 * 60, '/', null, false, true);


          Address::create([
            'account_id' => $account->id,
            'address1' => $this->individual_billing_address1,
            'address2' => $this->individual_billing_address2,
            'type' => 'billing',
            'country' => $this->individual_billing_country,
            'county' => $this->individual_billing_county,
            'city' => $this->individual_billing_city,
            'zipcode' => $this->individual_billing_zipcode
          ]);

          if (!$this->individual_identic) {
            Address::create([
              'account_id' => $account->id,
              'first_name' => $this->individual_shipping_first,
              'last_name' => $this->individual_shipping_last,
              'phone' => $this->individual_shipping_phone,
              'email' => $this->individual_shipping_email,
              'address1' => $this->individual_shipping_address1,
              'address2' => $this->individual_shipping_address2,
              'type' => 'shipping',
              'country' => $this->individual_shipping_country,
              'county' => $this->individual_shipping_county,
              'city' => $this->individual_shipping_city,
              'zipcode' => $this->individual_shipping_zipcode
            ]);
          } else {
            Address::create([
              'account_id' => $account->id,
              'first_name' => $this->individual_billing_first,
              'last_name' => $this->individual_billing_last,
              'phone' => $this->individual_billing_phone,
              'email' => $this->individual_billing_email,
              'address1' => $this->individual_billing_address1,
              'address2' => $this->individual_billing_address2,
              'type' => 'shipping',
              'country' => $this->individual_billing_country,
              'county' => $this->individual_billing_county,
              'city' => $this->individual_billing_city,
              'zipcode' => $this->individual_billing_zipcode
            ]);
          }
        }
      }
      if ($this->juridic) {
        if ($this->is_account != null) {
          Account::where('id', $this->is_account)->update([
            'name' => $this->juridic_billing_first . " " . $this->juridic_billing_last,
            'type' => 'juridic',
            'first_name' => $this->juridic_billing_first,
            'last_name' => $this->juridic_billing_last,
            'phone' => $this->juridic_billing_phone,
            'email' => $this->juridic_billing_email,
            'company_name' => $this->juridic_billing_company_name,
            'registration_code' => $this->juridic_billing_registration_code,
            'registration_number' => $this->juridic_billing_registration_number,
            'bank_name' => $this->juridic_billing_bank,
            'account' => $this->juridic_billing_account,
            'updated_at' => now(),
          ]);
          Address::where('account_id', $this->is_account)->where('type', 'billing')->update([
            'address1' => $this->juridic_billing_address1,
            'address2' => $this->juridic_billing_address2,
            'country' => $this->juridic_billing_country,
            'county' => $this->juridic_billing_county,
            'city' => $this->juridic_billing_city,
            'zipcode' => $this->juridic_billing_zipcode,
            'updated_at' => now(),
          ]);
          Address::where('account_id', $this->is_account)->where('type', 'shipping')->update([
            'first_name' => $this->juridic_shipping_first,
            'last_name' => $this->juridic_shipping_last,
            'phone' => $this->juridic_shipping_phone,
            'email' => $this->juridic_shipping_email,
            'address1' => $this->juridic_shipping_address1,
            'address2' => $this->juridic_shipping_address2,
            'country' => $this->juridic_shipping_country,
            'county' => $this->juridic_shipping_county,
            'city' => $this->juridic_shipping_city,
            'zipcode' => $this->juridic_shipping_zipcode,
            'updated_at' => now(),
          ]);
        } else {

          $account = Account::create([
            'name' => $this->juridic_billing_first . " " . $this->juridic_billing_last . ", " . $this->juridic_billing_company_name,
            'type' => 'juridic',
            'first_name' => $this->juridic_billing_first,
            'last_name' => $this->juridic_billing_last,
            'phone' => $this->juridic_billing_phone,
            'email' => $this->juridic_billing_email,
            'company_name' => $this->juridic_billing_company_name,
            'registration_code' => $this->juridic_billing_registration_code,
            'registration_number' => $this->juridic_billing_registration_number,
            'bank_name' => $this->juridic_billing_bank,
            'account' => $this->juridic_billing_account,
          ]);
          setcookie('accountId', $account->id, time() + 30 * 24 * 60 * 60, '/', null, false, true);


          Address::create([
            'account_id' => $account->id,
            'address1' => $this->juridic_billing_address1,
            'address2' => $this->juridic_billing_address2,
            'type' => 'billing',
            'country' => $this->juridic_billing_country,
            'county' => $this->juridic_billing_county,
            'city' => $this->juridic_billing_city,
            'zipcode' => $this->juridic_billing_zipcode
          ]);

          if (!$this->juridic_identic) {
            Address::create([
              'account_id' => $account->id,
              'first_name' => $this->juridic_shipping_first,
              'last_name' => $this->juridic_shipping_last,
              'phone' => $this->juridic_shipping_phone,
              'email' => $this->juridic_shipping_email,
              'address1' => $this->juridic_shipping_address1,
              'address2' => $this->juridic_shipping_address2,
              'type' => 'shipping',
              'country' => $this->juridic_shipping_country,
              'county' => $this->juridic_shipping_county,
              'city' => $this->juridic_shipping_city,
              'zipcode' => $this->juridic_shipping_zipcode
            ]);
          } else {
            Address::create([
              'account_id' => $account->id,
              'first_name' => $this->juridic_billing_first,
              'last_name' => $this->juridic_billing_last,
              'phone' => $this->juridic_billing_phone,
              'email' => $this->juridic_billing_email,
              'address1' => $this->juridic_billing_address1,
              'address2' => $this->juridic_billing_address2,
              'type' => 'shipping',
              'country' => $this->juridic_billing_country,
              'county' => $this->juridic_billing_county,
              'city' => $this->juridic_billing_city,
              'zipcode' => $this->juridic_billing_zipcode
            ]);
          }
        }
      }

      $baseName = class_basename(Order::class);
      $cartNumber = 1;
      $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
      while (Order::where('name', $uniqueName)->exists()) {
        $cartNumber++;
        $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
      }
      if ($this->is_account != null) {
        $accid = $this->is_account;
      } else {
        $accid = $account->id;
      }
      if ($this->payment['type'] != 'card') {

        $order = Order::create([
          'name' => $uniqueName,
          'session_id' => $this->session_id,
          'account_id' => $accid,
          'cart_id' => $this->cart->id,
          'quantity_amount' => $this->cart->quantity_amount,
          'sum_amount' => $this->cart->sum_amount,
          'final_amount' => ($this->cart->sum_amount + app('global_delivery_price') - $this->cart->voucher_value),
          'delivery_price' => app('global_delivery_price'),
          'voucher_value' => $this->cart->voucher_value ?? 0,
          'currency_id' => $this->cart->currency_id,
          'status_id' =>  app('global_order_processing'),
          'payment_id' => $this->payment['id'],
          'voucher_id' =>  $this->cart->voucher_id
        ]);
      } else {
        $order = Order::create([
          'name' => $uniqueName,
          'session_id' => $this->session_id,
          'account_id' => $accid,
          'cart_id' => $this->cart->id,
          'quantity_amount' => $this->cart->quantity_amount,
          'sum_amount' => $this->cart->sum_amount,
          'final_amount' => ($this->cart->sum_amount + app('global_delivery_price') - $this->cart->voucher_value),
          'delivery_price' => app('global_delivery_price'),
          'voucher_value' => $this->cart->voucher_value ?? 0,
          'currency_id' => $this->cart->currency_id,
          'status_id' =>  app('global_order_check_payment'),
          'payment_id' => $this->payment['id'],
          'voucher_id' =>  $this->cart->voucher_id
        ]);
      }

      $this->orderNumber = app('global_order_prefix') . now()->format('Ymd') . str_pad($order->id, 3, '0', STR_PAD_LEFT);
      $order->update([
        'order_number' => $this->orderNumber
      ]);

      foreach ($this->cartitems as $item) {
        $item->product->quantity -= $item->quantity;
        $item->product->save();

        Order_Item::create([
          'order_id' => $order->id,
          'product_id' => $item->product_id,
          'price' => $item->price,
          'quantity' => $item->quantity,
        ]);
      }

      if ($this->cart->voucher && $this->cart->voucher->single_use) {
        Voucher::where('id', $this->cart->voucher_id)->update([
          'status_id' => app('global_voucher_closed')
        ]);
      }
      if ($this->payment['type'] != 'card') {

        $this->cart->update([
          'order_id' => $order->id,
          'status_id' => app('global_cart_closed')
        ]);

        $this->step++;
        $this->emit('orderprocess');
      } else {
        $this->cart->update([
          'order_id' => $order->id,
          'status_id' => app('global_check_payment')
        ]);
      }
    }
  }
}
