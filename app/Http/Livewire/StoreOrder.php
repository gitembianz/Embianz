<?php

namespace App\Http\Livewire;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Account;
use App\Models\Address;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\Order_Item;
use Stripe\Checkout\Session;
use GuzzleHttp\Client;

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
  public $payment_cancel = false;
  public $new_order;
  public $modification = false;

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
  public $crd = true;
  public $invoice = false;
  public $validatequantity = true;
  public $payment;
  protected $listeners = [
    'nocard' => 'mount',
    'cartUpdated' => 'mount',
    'isdisabled' => 'checkIsDisabled'
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

  public function checkIsDisabled()
  {
    $this->modification = true;
  }

  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount', 'currency_id', 'delivery_price', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value', 'status_id')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->with(['voucher' => function ($query) {
        $query->select('code', 'id', 'percent', 'single_use', 'value', 'start_date', 'end_date');
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
            $query->select('id', 'name', 'seo_id', 'quantity', 'active', 'start_date', 'end_date')->with([
              'media' => function ($query) {
                $query->select('path', 'name')->where('type', 'min');
              },
              'product_prices' => function ($query) {
                $query->select('product_id', 'value', 'pricelist_id')
                  ->with(['pricelist' => function ($query) {
                    $query->select('id', 'currency_id')->with('currency:id,name,symbol');
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
          'max:20',
          'regex:/^[\p{L}\s]+$/u',
        ],
        'individual_billing_last' => [
          'required',
          'min:2',
          'max:20',
          'regex:/^[\p{L}\s]+$/u',

        ],
        'individual_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
        'individual_billing_email' => 'required|email',
        'individual_billing_address1' => [
          'required',
          'min:1',
          'max:200',
        ],
        'individual_billing_county' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[\p{L}\s]+$/u',
        ],
        'individual_billing_city' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[\p{L}\s]+$/u',
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
            'regex:/^[\p{L}\s]+$/u',
          ],
          'individual_shipping_last' => [
            'required',
            'min:2',
            'max:20',
            'regex:/^[\p{L}\s]+$/u',
          ],
          'individual_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
          'individual_shipping_email' => 'required|email',
          'individual_shipping_address1' =>
          [
            'required',
            'min:1',
            'max:200',
          ],
          'individual_shipping_county' =>
          [
            'required',
            'min:1',
            'max:100',
            'regex:/^[\p{L}\s]+$/u',
          ],
          'individual_shipping_city' =>
          [
            'required',
            'min:1',
            'max:100',
            'regex:/^[\p{L}\s]+$/u',
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
          'regex:/^[\p{L}\s]+$/u',
        ],
        'juridic_billing_last' => [
          'required',
          'min:2',
          'max:20',
          'regex:/^[\p{L}\s]+$/u',
        ],
        'juridic_billing_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
        'juridic_billing_email' => 'required|email',
        'juridic_billing_company_name' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/'
        ],
        'juridic_billing_registration_code' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/'
        ],
        'juridic_billing_registration_number' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/'
        ],
        'juridic_billing_address1' => [
          'required',
          'min:1',
          'max:200',
        ],
        'juridic_billing_county' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[\p{L}\d\s.]+$/u',
        ],
        'juridic_billing_city' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[\p{L}\d\s.]+$/u',
        ],
        'juridic_billing_zipcode' =>
        [
          'required',
          'min:1',
          'max:100',
          'regex:/^[a-zA-Z0-9\/., _\'`-]+$/',
        ]
      ];
      if (!$this->juridic_identic) {
        $shippingRules = [
          'juridic_shipping_first' => [
            'required',
            'min:2',
            'max:20',
            'regex:/^[\p{L}\s]+$/u',
          ],
          'juridic_shipping_last' => [
            'required',
            'min:2',
            'max:20',
            'regex:/^[\p{L}\s]+$/u',
          ],
          'juridic_shipping_phone' => 'required|regex:/^\+?\d{1,4}?\s?\(?\d{1,4}\)?[-.\s]?\d{1,10}[-.\s]?\d{1,10}$/',
          'juridic_shipping_email' => 'required|email',
          'juridic_shipping_address1' => [
            'required',
            'min:1',
            'max:200',
          ],
          'juridic_shipping_county' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[\p{L}\d\s.]+$/u',
          ],
          'juridic_shipping_city' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[\p{L}\d\s.]+$/u',
          ],
          'juridic_shipping_zipcode' => [
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
  }

  public function togglepayment($item)
  {
    if ($item == 'rtc') {
      $this->payment = $this->cash;
      $this->rtc = true;
      $this->crd = false;
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
    $this->modification = false;
    $this->session_id = $this->getSessionId();
    if (session()->has('paymentcancel')) {
      $this->payment_cancel = true;
      $this->step = 2;
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
      if (app()->has("global_send_to_salesforce") && app('global_send_to_salesforce') == 'true') {
        //request to salesforce
        $order_lines = [];
        foreach ($order->orders as $sub_order) {
          $order_line = [
            '00N9N000000QGYY' => $sub_order->order_id,
            '00N9N000000QGYT' => $sub_order->id,
            '00N9N000000QGYd' => $sub_order->product_id,
            '00N9N000000QGZC' => $sub_order->quantity,
            '00NQF000000Yzc9' => $sub_order->price
          ];
          $order_lines[] = $order_line;
        }
        $client = new Client();
        $order_lines_string = json_encode($order_lines);
        $hashed = base64_encode($order->order_number . ";" . $order->account->email);

        $client->post('https://webto.salesforce.com/servlet/servlet.WebToCase', [
          'headers' => [
            'Accept' => 'application/json',
          ],
          'query' => [
            'orgid' => '00D09000008XPQu',
            'debug' => '1',
            'debugEmail' => 'iosif.relia@eztemcorp.com',
            'subject' => app()->has('global_site_name') && app('global_site_name') != "" ?
              app('global_site_name') . ' Order_id:' . $order->id :
              'Order_id:' . $order->id,
            '00N9N000000QGVe' => app()->has('global_site_url') && app('global_site_url') != "" ?
              app('global_site_url') : 'Verifica site url-ul proiectului',

            'type' => 'Store Order Master',

            //hasedinfo
            '00NQF000000VDVd' => $hashed,


            //order
            '00N9N000000QGVZ' => $order->id,
            '00N9N000000QGVj' => $order->order_number,
            '00N9N000000QGVo' => $order->status->name,
            '00N9N000000QGVt' => $order->currency->name,
            '00N9N000000QGVy' => $order->payment->name,
            '00N9N000000QGW3' => $order->voucher->code ?? "",
            '00N9N000000QGW8' => $order->voucher_value,
            '00N9N000000QGWI' => $order->delivery_price,
            '00N9N000000QGWN' => $order->final_amount,

            //orderlines
            '00N9N000000QGYi' => $order_lines_string,

            //account
            '00N9N000000QGWS' => $order->account->id,
            '00N9N000000QGYx' => $order->account->type,
            '00N9N000000QGWX' => $order->account->company_name ?? "",
            '00N9N000000QGWc' => $order->account->registration_code ?? "",
            '00N9N000000QGWh' => $order->account->registration_number ?? "",
            '00N9N000000QGWr' => $order->account->bank_name ?? "",
            '00N9N000000QGWw' => $order->account->account ?? "",
            //billing adress
            '00N9N000000QGX1' => $order->account->addresses->where('type', 'billing')->first()->address1,
            '00N9N000000QGX6' => $order->account->addresses->where('type', 'billing')->first()->address2,
            '00N9N000000QGXB' => $order->account->addresses->where('type', 'billing')->first()->country,
            '00N9N000000QGXG' => $order->account->addresses->where('type', 'billing')->first()->county,
            '00N9N000000QGXL' => $order->account->addresses->where('type', 'billing')->first()->city,
            '00N9N000000QGXQ' => $order->account->addresses->where('type', 'billing')->first()->zipcode,
            '00N9N000000QGXV' => $order->account->addresses->where('type', 'billing')->first()->first_name ?? "",
            '00N9N000000QGXa' => $order->account->addresses->where('type', 'billing')->first()->last_name ?? "",
            '00N9N000000QGXf' => $order->account->addresses->where('type', 'billing')->first()->phone ?? "",
            '00N9N000000QGXk' => $order->account->addresses->where('type', 'billing')->first()->email ?? "",
            //shipping adress
            '00N9N000000QGXp' => $order->account->addresses->where('type', 'shipping')->first()->address1,
            '00N9N000000QGXu' => $order->account->addresses->where('type', 'shipping')->first()->address2,
            '00N9N000000QGXz' => $order->account->addresses->where('type', 'shipping')->first()->country,
            '00N9N000000QGWi' => $order->account->addresses->where('type', 'shipping')->first()->county,
            '00N9N000000QGY4' => $order->account->addresses->where('type', 'shipping')->first()->city,
            '00N9N000000QGWE' => $order->account->addresses->where('type', 'shipping')->first()->zipcode,
            '00N9N000000QGY9' => $order->account->addresses->where('type', 'shipping')->first()->first_name ?? "",
            '00N9N000000QGYE' => $order->account->addresses->where('type', 'shipping')->first()->last_name ?? "",
            '00N9N000000QGYJ' => $order->account->addresses->where('type', 'shipping')->first()->phone ?? "",
            '00N9N000000QGYO' => $order->account->addresses->where('type', 'shipping')->first()->email ?? "",
          ],
          'curl' => [
            CURLOPT_SSL_VERIFYPEER => false,
          ],
        ]);
      }
      session()->forget('paymentsucces');
    }

    if ($this->cartItems->isEmpty() || !$this->cart) {
      $this->back = true;
    }
    if (array_key_exists('accountId', $_COOKIE)) {
      $this->is_account = $_COOKIE['accountId'];
    } else {
      $this->is_account = null;
    }
    if ($this->is_account != null) {
      $account = Account::with('addresses', 'orders')->find($this->is_account) ?? null;
      if ($account == null) {
        unset($_COOKIE['accountId']);
        $this->is_account = null;
      } else {

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
    }
    $this->cash = app('global_cash');
    $this->card = app('global_card_stripe');
    $this->ordin = app('global_ordin');
    $this->payment = $this->card;

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
        if (($this->cart->voucher->status_id == app('global_voucher_closed')) || ($this->cart->voucher->start_date > now()->format('Y-m-d')) || ($this->cart->voucher->end_date < now()->format('Y-m-d'))) {

          if (app()->has('global_order_error_voucher')) {
            $message = app('global_order_error_voucher');
          } else {
            $message = "Vă rog verificați detaliile comenzii!";
          }
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
      foreach ($this->cartitems as $item) {
        if ($item->quantity > $item->product->quantity) {
          $this->validatequantity = false;
          if (app()->has('global_order_error_quantity')) {
            $message = app('global_order_error_quantity');
          } else {
            $message = "Vă rog verificați detaliile comenzii!";
          }
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          return;
        }
      }
      foreach ($this->cartitems as $item) {
        if (($item->product->active != true) || ($item->product->start_date > now()->format('Y-m-d')) || ($item->product->end_date < now()->format('Y-m-d'))) {
          if (app()->has('global_order_error_active')) {
            $message = app('global_order_error_active');
          } else {
            $message = "Vă rog verificați detaliile comenzii!";
          }
          $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
          return;
        }
      }
    } else {
      $this->emit('cartUpdated');
      $this->validatequantity = false;
      if (app()->has('global_order_error_cart')) {
        $message = app('global_order_error_cart');
      } else {
        $message = "Vă rog verificați detaliile comenzii!";
      }
      $this->dispatchBrowserEvent('alert__modal', ['message' => $message]);
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
            'first_name' => $this->individual_billing_first,
            'last_name' => $this->individual_billing_last,
            'phone' => $this->individual_billing_phone,
            'email' => $this->individual_billing_email,
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
            'first_name' => $this->individual_billing_first,
            'last_name' => $this->individual_billing_last,
            'phone' => $this->individual_billing_phone,
            'email' => $this->individual_billing_email,
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
            'first_name' => $this->juridic_billing_first,
            'last_name' => $this->juridic_billing_last,
            'phone' => $this->juridic_billing_phone,
            'email' => $this->juridic_billing_email,
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
            'first_name' => $this->juridic_billing_first,
            'last_name' => $this->juridic_billing_last,
            'phone' => $this->juridic_billing_phone,
            'email' => $this->juridic_billing_email,
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
        if ($this->payment['type'] != 'card') {
          $item->product->quantity -= $item->quantity;
          $item->product->save();
        }

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

        $this->step = 3;
        $this->new_order = $order;

        $this->emit('orderprocess');
        if (app()->has("global_send_to_salesforce") && app('global_send_to_salesforce') == 'true') {

          //request to salesforce
          $order_lines = [];
          foreach ($order->orders as $sub_order) {
            $order_line = [
              '00N9N000000QGYY' => $sub_order->order_id,
              '00N9N000000QGYT' => $sub_order->id,
              '00N9N000000QGYd' => $sub_order->product_id,
              '00N9N000000QGZC' => $sub_order->quantity,
              '00NQF000000Yzc9' => $sub_order->price
            ];
            $order_lines[] = $order_line;
          }
          $client = new Client();
          $order_lines_string = json_encode($order_lines);
          $hashed = base64_encode($order->order_number);
          $client->post('https://webto.salesforce.com/servlet/servlet.WebToCase', [
            'headers' => [
              'Accept' => 'application/json',
            ],
            'query' => [
              'orgid' => '00D09000008XPQu',
              'debug' => '1',
              'debugEmail' => 'iosif.relia@eztemcorp.com',
              'subject' => app()->has('global_site_name') && app('global_site_name') != "" ?
                app('global_site_name') . ' Order_id:' . $order->id :
                'Order_id:' . $order->id,
              '00N9N000000QGVe' => app()->has('global_site_url') && app('global_site_url') != "" ?
                app('global_site_url') : 'Verifica site url-ul proiectului',
              'type' => 'Store Order Master',

              //hasedinfo
              '00NQF000000VDVd' => $hashed,

              //order
              '00N9N000000QGVZ' => $order->id,
              '00N9N000000QGVj' => $order->order_number,
              '00N9N000000QGVo' => $order->status->name,
              '00N9N000000QGVt' => $order->currency->name,
              '00N9N000000QGVy' => $order->payment->type,
              '00N9N000000QGW3' => $order->voucher->code ?? "",
              '00N9N000000QGW8' => $order->voucher_value,
              '00N9N000000QGWI' => $order->delivery_price,
              '00N9N000000QGWN' => $order->final_amount,

              //orderlines
              '00N9N000000QGYi' => $order_lines_string,

              //account
              '00N9N000000QGWS' => $order->account->id,
              '00N9N000000QGYx' => $order->account->type,
              '00N9N000000QGWX' => $order->account->company_name ?? "",
              '00N9N000000QGWc' => $order->account->registration_code ?? "",
              '00N9N000000QGWh' => $order->account->registration_number ?? "",
              '00N9N000000QGWr' => $order->account->bank_name ?? "",
              '00N9N000000QGWw' => $order->account->account ?? "",
              //billing adress
              '00N9N000000QGX1' => $order->account->addresses->where('type', 'billing')->first()->address1,
              '00N9N000000QGX6' => $order->account->addresses->where('type', 'billing')->first()->address2,
              '00N9N000000QGXB' => $order->account->addresses->where('type', 'billing')->first()->country,
              '00N9N000000QGXG' => $order->account->addresses->where('type', 'billing')->first()->county,
              '00N9N000000QGXL' => $order->account->addresses->where('type', 'billing')->first()->city,
              '00N9N000000QGXQ' => $order->account->addresses->where('type', 'billing')->first()->zipcode,
              '00N9N000000QGXV' => $order->account->addresses->where('type', 'billing')->first()->first_name ?? "",
              '00N9N000000QGXa' => $order->account->addresses->where('type', 'billing')->first()->last_name ?? "",
              '00N9N000000QGXf' => $order->account->addresses->where('type', 'billing')->first()->phone ?? "",
              '00N9N000000QGXk' => $order->account->addresses->where('type', 'billing')->first()->email ?? "",
              //shipping adress
              '00N9N000000QGXp' => $order->account->addresses->where('type', 'shipping')->first()->address1,
              '00N9N000000QGXu' => $order->account->addresses->where('type', 'shipping')->first()->address2,
              '00N9N000000QGXz' => $order->account->addresses->where('type', 'shipping')->first()->country,
              '00N9N000000QGWi' => $order->account->addresses->where('type', 'shipping')->first()->county,
              '00N9N000000QGY4' => $order->account->addresses->where('type', 'shipping')->first()->city,
              '00N9N000000QGWE' => $order->account->addresses->where('type', 'shipping')->first()->zipcode,
              '00N9N000000QGY9' => $order->account->addresses->where('type', 'shipping')->first()->first_name ?? "",
              '00N9N000000QGYE' => $order->account->addresses->where('type', 'shipping')->first()->last_name ?? "",
              '00N9N000000QGYJ' => $order->account->addresses->where('type', 'shipping')->first()->phone ?? "",
              '00N9N000000QGYO' => $order->account->addresses->where('type', 'shipping')->first()->email ?? "",
            ],
            'curl' => [
              CURLOPT_SSL_VERIFYPEER => false,
            ],
          ]);
        }
        $this->dispatchBrowserEvent('goup');
      } else {
        $this->cart->update([
          'order_id' => $order->id,
          'status_id' => app('global_cart_check_payment')
        ]);

        Stripe::setApiKey(app('global_stripe_key'));

        $session = Session::create([
          'line_items' => [
            [
              'price_data' => [
                'currency' => $order->currency->name,
                'product_data' => [
                  'name' =>  $order->order_number,
                ],
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
