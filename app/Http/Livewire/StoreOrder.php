<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Account;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Voucher;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\Order_Item;

class StoreOrder extends Component
{
  public $step = 1;
  public $back = false;
  public $terms = false;
  public $errorterms = false;
  public $session_id;
  public $cash;
  public $ordin;
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
    return Cart::select('id', 'quantity_amount', 'sum_amount', 'voucher_id', 'final_amount', 'voucher_value')
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
            $query->select('id', 'name', 'seo_id')->with([
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
        'status_id' => app('global_cart_checkoutpayment')
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
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
        ],
        'individual_billing_county' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
        ],
        'individual_billing_city' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
        ],
        'individual_billing_zipcode' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
        ]
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
          'individual_shipping_address1' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
          ],
          'individual_shipping_county' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
          ],
          'individual_shipping_city' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
          ],
          'individual_shipping_zipcode' => [
            'required',
            'min:1',
            'max:100',
            'regex:/^[^\s]+(\s+[^\s]+)*$/',
            'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
          ]
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
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
        ],
        'juridic_billing_county' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
        ],
        'juridic_billing_city' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
        ],
        'juridic_billing_zipcode' => [
          'required',
          'min:1',
          'max:100',
          'regex:/^[^\s]+(\s+[^\s]+)*$/',
          'regex:/^[a-zA-Z0-9\/., _\'`-]*$/'
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
      $this->payment = $this->cash['description'];
      $this->rtc = true;
      $this->invoice = false;
    }
    if ($item == 'invoice') {
      $this->payment = $this->ordin['description'];
      $this->rtc = false;
      $this->invoice = true;
    }
  }

  public function mount()
  {
    $this->session_id = $this->getSessionId();
    $this->cash = app('global_cash');
    $this->ordin = app('global_ordin');
    $this->payment = $this->cash['description'];
    if ($this->cartItems->isEmpty() || !$this->cart) {
      $this->back = true;
    }
    if ($this->step == 2) {
      $this->cart->update([
        'status_id' => app('global_cart_checkoutpayment')
      ]);
    }
    $this->individual_billing_country = app('global_default_country');
    $this->individual_shipping_country = app('global_default_country');
    $this->juridic_billing_country = app('global_default_country');
    $this->juridic_shipping_country = app('global_default_country');
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

    if ($this->cartitems && ($this->cart->status_id == app('global_cart_checkoutpayment'))) {
      foreach ($this->cartitems as $item) {
        if ($item->quantity >= $item->product->quantity) {
          $this->validatequantity = false;
          $this->dispatchBrowserEvent('alert__modal');
          return;
        } else {
          $this->validatequantity = true;
        }
      }
    } else {
      $this->emit('cartUpdated');
      $this->validatequantity = false;
      $this->dispatchBrowserEvent('alert__modal');
    }
    if ($this->validatequantity) {

      if ($this->individual) {
        $account = new Account();
        $account->name = $this->individual_billing_first . " " . $this->individual_billing_last;
        $account->type = 'individual';
        $account->first_name = $this->individual_billing_first;
        $account->last_name = $this->individual_billing_last;
        $account->phone = $this->individual_billing_phone;
        $account->email = $this->individual_billing_email;
        $account->save();

        $address = new Address();
        $address->account_id = $account->id;
        $address->address1 = $this->individual_billing_address1;
        $address->address2 = $this->individual_billing_address2;
        $address->country = $this->individual_billing_country;
        $address->county = $this->individual_billing_county;
        $address->city = $this->individual_billing_city;
        $address->zipcode = $this->individual_billing_zipcode;
        $address->type = 'billing';
        $address->save();

        if ($this->individual_identic == false) {
          $address_shipping = new Address();
          $address_shipping->account_id = $account->id;
          $address_shipping->first_name = $this->individual_shipping_first;
          $address_shipping->last_name = $this->individual_shipping_last;
          $address_shipping->phone = $this->individual_shipping_phone;
          $address_shipping->email = $this->individual_shipping_email;
          $address_shipping->address1 = $this->individual_shipping_address1;
          $address_shipping->address2 = $this->individual_shipping_address2;
          $address_shipping->country = $this->individual_shipping_country;
          $address_shipping->county = $this->individual_shipping_county;
          $address_shipping->city = $this->individual_shipping_city;
          $address_shipping->zipcode = $this->individual_shipping_zipcode;
          $address_shipping->type = 'shipping';
          $address_shipping->save();
        } else {
          $address_shipping = new Address();
          $address_shipping->account_id = $account->id;
          $address_shipping->first_name = $this->individual_billing_first;
          $address_shipping->last_name = $this->individual_billing_last;
          $address_shipping->phone = $this->individual_billing_phone;
          $address_shipping->email = $this->individual_billing_email;
          $address_shipping->address1 = $this->individual_billing_address1;
          $address_shipping->address2 = $this->individual_billing_address2;
          $address_shipping->country = $this->individual_billing_country;
          $address_shipping->county = $this->individual_billing_county;
          $address_shipping->city = $this->individual_billing_city;
          $address_shipping->zipcode = $this->individual_billing_zipcode;
          $address_shipping->type = 'shipping';
          $address_shipping->save();
        }
      }
      if ($this->juridic) {
        $account = new Account();
        $account->name = $this->juridic_billing_first . " " . $this->juridic_billing_last . ", " . $this->juridic_billing_company_name;
        $account->type = 'juridic';
        $account->first_name = $this->juridic_billing_first;
        $account->last_name = $this->juridic_billing_last;
        $account->phone = $this->juridic_billing_phone;
        $account->email = $this->juridic_billing_email;
        $account->company_name = $this->juridic_billing_company_name;
        $account->registration_code = $this->juridic_billing_registration_code;
        $account->registration_number = $this->juridic_billing_registration_number;
        $account->bank_name = $this->juridic_billing_bank;
        $account->account = $this->juridic_billing_account;
        $account->save();

        $address = new Address();
        $address->account_id = $account->id;
        $address->address1 = $this->juridic_billing_address1;
        $address->address2 = $this->juridic_billing_address2;
        $address->country = $this->juridic_billing_country;
        $address->county = $this->juridic_billing_county;
        $address->city = $this->juridic_billing_city;
        $address->zipcode = $this->juridic_billing_zipcode;
        $address->type = 'billing';
        $address->save();

        if ($this->juridic_identic == false) {
          $address_shipping = new Address();
          $address_shipping->account_id = $account->id;
          $address_shipping->first_name = $this->juridic_billing_first;
          $address_shipping->last_name = $this->juridic_billing_last;
          $address_shipping->phone = $this->juridic_billing_phone;
          $address_shipping->email = $this->juridic_billing_email;
          $address_shipping->address1 = $this->juridic_billing_address1;
          $address_shipping->address2 = $this->juridic_billing_address2;
          $address_shipping->country = $this->juridic_billing_country;
          $address_shipping->county = $this->juridic_billing_county;
          $address_shipping->city = $this->juridic_billing_city;
          $address_shipping->zipcode = $this->juridic_billing_zipcode;
          $address_shipping->type = 'shipping';
          $address_shipping->save();
        } else {
          $address_shipping = new Address();
          $address_shipping->account_id = $account->id;
          $address_shipping->first_name = $this->juridic_shipping_first;
          $address_shipping->last_name = $this->juridic_shipping_last;
          $address_shipping->phone = $this->juridic_shipping_phone;
          $address_shipping->email = $this->juridic_shipping_email;
          $address_shipping->address1 = $this->juridic_shipping_address1;
          $address_shipping->address2 = $this->juridic_shipping_address2;
          $address_shipping->country = $this->juridic_shipping_country;
          $address_shipping->county = $this->juridic_shipping_county;
          $address_shipping->city = $this->juridic_shipping_city;
          $address_shipping->zipcode = $this->juridic_shipping_zipcode;
          $address_shipping->type = 'shipping';
          $address_shipping->save();
        }
      }
      $baseName = class_basename(Order::class); // Gets the base name of the Cart model class (e.g., "Cart")
      $cartNumber = 1;
      $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);

      // Check for uniqueness, generate a new name if it's not unique
      while (Order::where('name', $uniqueName)->exists()) {
        $cartNumber++;
        $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
      }
      $paymentId = Payment::where('name', $this->delivery)->first()->id;
      Order::create([
        'name' => $uniqueName,
        'session_id' => $this->session_id,
        'account_id' => $account->id,
        'cart_id' => $this->cart->id,
        'quantity_amount' => $this->cart->quantity_amount,
        'sum_amount' => $this->cart->final_amount,
        'currency_id' => $this->cart->currency_id,
        'status_id' =>  app('global_order_new'),
        'payment_id' => $paymentId,
        'voucher_id' =>  $this->cart->voucher_id,
      ]);
      if ($this->cartitems) {
        $order = Order::where('cart_id', $this->cart->id)->first();
        $orderNumber = 'NRN' . now()->format('Ymd') . str_pad($order->id, 3, '0', STR_PAD_LEFT);

        // Update the order with the generated order number
        $order->order_number = $orderNumber;
        $order->save();
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
        $this->cart->order_id = $order->id;
        if ($this->cart->voucher) {
          if ($this->cart->voucher->single_use) {
            $vouch = Voucher::find($this->cart->voucher_id);
            $vouch->status_id = app('global_voucher_closed');
            $vouch->save();
          }
        }
      }
      $this->cart->status_id = app('global_cart_closed');
      $this->cart->save();
      $this->step++;
      $this->emit('orderprocess');
    }
  }
}
