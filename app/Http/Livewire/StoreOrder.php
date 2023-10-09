<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_Item;
use App\Models\Juridic;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\Individual;


class StoreOrder extends Component
{
  public $step = 1;
  public $individual = true;
  public $juridic = false;
  public $individual_identic = false;
  public $juridic_identic = false;
  public $back = false;
  public $session_id;
  public $cart;

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
  protected $listeners = [
    'nocard' => 'mount',
  ];

  //declaration juridic person
  public function render()
  {
    if ($this->step == 2) {
      $data = [
        'cartItems' => $this->cartItems,
      ];
      return view('livewire.store-order', $data);
    } else {
      return view('livewire.store-order');
    }
  }
  public function mount()
  {
    $this->session_id = $_COOKIE['sessionId'];
    $this->cart = Cart::where('session_id', $this->session_id)->where('status', 'in progress')->orwhere('status', 'order')->first();
    if ($this->cart === null) {
      $this->back = true;
    }
    $this->resetForm();
    $this->step = 1;
    $this->individual_identic = false;
    $this->juridic_identic = false;
  }
  public function showindividual()
  {
    $this->resetForm();
    $this->individual = true;
    $this->juridic = false;
    $this->juridic_identic = false;
    $this->step = 1;
  }
  public function finish()
  {
    //Finish order code
    return redirect('/home');
  }
  public function getCartItemsProperty()
  {
    if ($this->cart !== null) {
      $cartItems = Cart_Item::where('cart_id', $this->cart->id)->with('product')->get();
      return $cartItems;
    }
    return collect(); // Return an empty collection if no cart items are found
  }
  public function showjuridic()
  {
    $this->resetForm();
    $this->individual = false;
    $this->juridic = true;
    $this->individual_identic = false;
    $this->step = 1;
  }
  public function confirm()
  {
    if ($this->individual) {
      Individual::create([
        'session_id' => session()->getId(),
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
        'type' => 'billing',
      ]);
      if ($this->individual_identic == false) {
        Individual::create([
          'session_id' => session()->getId(),
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
          'type' => "shipping",
        ]);
      } else {
        Individual::create([
          'session_id' => session()->getId(),
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
          'type' => 'shipping',
        ]);
      }
    }
    if ($this->juridic) {
      Juridic::create([
        'session_id' => session()->getId(),
        'first_name' => $this->juridic_billing_first,
        'last_name' => $this->juridic_billing_last,
        'phone' => $this->juridic_billing_phone,
        'email' => $this->juridic_billing_email,
        'company_name' => $this->juridic_billing_company_name,
        'registration_code' => $this->juridic_billing_registration_code,
        'registration_number' => $this->juridic_billing_registration_number,
        'bank' => $this->juridic_billing_bank,
        'account' => $this->juridic_billing_account,
        'address1' => $this->juridic_billing_address1,
        'address2' => $this->juridic_billing_address2,
        'country' => $this->juridic_billing_country,
        'county' => $this->juridic_billing_county,
        'city' => $this->juridic_billing_city,
        'zipcode' => $this->juridic_billing_zipcode,
        'type' => 'billing',
      ]);
      if ($this->juridic_identic == false) {
        Juridic::create([
          'session_id' => session()->getId(),
          'first_name' => $this->juridic_billing_first,
          'last_name' => $this->juridic_billing_last,
          'phone' => $this->juridic_billing_phone,
          'email' => $this->juridic_billing_email,
          'company_name' => $this->juridic_billing_company_name,
          'registration_code' => $this->juridic_billing_registration_code,
          'registration_number' => $this->juridic_billing_registration_number,
          'bank' => $this->juridic_billing_bank,
          'account' => $this->juridic_billing_account,
          'address1' => $this->juridic_billing_address1,
          'address2' => $this->juridic_billing_address2,
          'country' => $this->juridic_billing_country,
          'county' => $this->juridic_billing_county,
          'city' => $this->juridic_billing_city,
          'zipcode' => $this->juridic_billing_zipcode,
          'type' => 'shipping',
        ]);
      } else {
        Juridic::create([
          'session_id' => session()->getId(),
          'first_name' => $this->juridic_shipping_first,
          'last_name' => $this->juridic_shipping_last,
          'phone' => $this->juridic_shipping_phone,
          'email' => $this->juridic_shipping_email,
          'phone' => $this->juridic_shipping_phone,
          'company_name' => $this->juridic_billing_company_name,
          'registration_code' => $this->juridic_billing_registration_code,
          'registration_number' => $this->juridic_billing_registration_number,
          'bank' => $this->juridic_billing_bank,
          'account' => $this->juridic_billing_account,
          'address1' => $this->juridic_shipping_address1,
          'address2' => $this->juridic_shipping_address2,
          'country' => $this->juridic_shipping_country,
          'county' => $this->juridic_shipping_county,
          'city' => $this->juridic_shipping_city,
          'zipcode' => $this->juridic_shipping_zipcode,
          'type' => 'shipping',
        ]);
      }
    }
    Order::create([
      'session_id' => $this->session_id,
      'quantity_amount' => $this->cart->quantity_amount,
      'sum_amount' => $this->cart->final_amount,
      'currency_id' => $this->cart->currency_id,
      'status' => 'in progress',
    ]);
    $cartitems = Cart_Item::where('cart_id', $this->cart->id)->get();
    if ($cartitems) {
      $order = Order::where('session_id', $this->session_id)->first();
      foreach ($cartitems as $item) {
        Order_Item::create([
          'order_id' => $order->id,
          'product_id' => $item->product_id,
          'price' => $item->price,
          'quantity' => $item->quantity,
        ]);
      }
    }
    $this->cart->status = "closed";
    $this->cart->save();
    $this->step++;
    $this->emit('cartUpdated');
  }
  public function next()
  {
    $cartt = Cart::where('session_id', session()->getId())->first();
    if ($cartt != null) {
      $this->resetErrorBag();
      $this->validateData();
      $this->step++;
      if ($this->individual_identic == true) {
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
      if ($this->juridic_identic == true) {
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
    } else {
      $this->emit('nocard');
    }
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
      'individual_billing_address1', 'individual_billing_address2', 'individual_billing_country', 'individual_billing_county',
      'individual_billing_city', 'individual_billing_zipcode',
      'individual_shipping_first', 'individual_shipping_last', 'individual_shipping_phone', 'individual_shipping_email',
      'individual_shipping_address1', 'individual_shipping_address2', 'individual_shipping_country', 'individual_shipping_county',
      'individual_shipping_city', 'individual_shipping_zipcode',
      'juridic_billing_first', 'juridic_billing_last', 'juridic_billing_phone', 'juridic_billing_email',
      'juridic_billing_company_name', 'juridic_billing_registration_code', 'juridic_billing_registration_number',
      'juridic_billing_bank', 'juridic_billing_account',
      'juridic_billing_address1', 'juridic_billing_address2', 'juridic_billing_country', 'juridic_billing_county',
      'juridic_billing_city', 'juridic_billing_zipcode',
      'juridic_shipping_first', 'juridic_shipping_last', 'juridic_shipping_phone', 'juridic_shipping_email',
      'juridic_shipping_address1', 'juridic_shipping_address2', 'juridic_shipping_country', 'juridic_shipping_county',
      'juridic_shipping_city', 'juridic_shipping_zipcode'
    ]);
  }
  public function validateData()
  {

    if ($this->step == 1) {
      if ($this->individual) {
        $rules = [
          'individual_billing_first' => 'required|string',
          'individual_billing_last' => 'required|string',
          'individual_billing_phone' => 'required|numeric',
          'individual_billing_email' => 'required|email',
          'individual_billing_address1' => 'required|string',
          'individual_billing_country' => 'required|string',
          'individual_billing_city' => 'required|string',
          'individual_billing_zipcode' => 'required|string',
        ];
        // Address line 2 and county are optional, so we don't need to include them in the validation unless they are provided.
        if (!empty($this->individual_billing_address2)) {
          $rules['individual_billing_address2'] = 'string';
        }

        if (!empty($this->individual_billing_county)) {
          $rules['individual_billing_county'] = 'string';
        }

        if (!$this->individual_identic) {
          // Include shipping address rules only if individual_identic is false
          $shippingRules = [
            'individual_shipping_first' => 'required|string',
            'individual_shipping_last' => 'required|string',
            'individual_shipping_phone' => 'required|numeric',
            'individual_shipping_email' => 'required|email',
            'individual_shipping_address1' => 'required|string',
            'individual_shipping_country' => 'required|string',
            'individual_shipping_city' => 'required|string',
            'individual_shipping_zipcode' => 'required|string',
          ];

          // Address line 2 and county are optional for shipping address too
          if (!empty($this->individual_shipping_address2)) {
            $shippingRules['individual_shipping_address2'] = 'string';
          }

          if (!empty($this->individual_shipping_county)) {
            $shippingRules['individual_shipping_county'] = 'string';
          }

          // Merge shipping address rules with existing rules
          $rules = array_merge($rules, $shippingRules);
        }

        $this->validate($rules);
      }
      if ($this->juridic) {
        $rules = [
          'juridic_billing_first' => 'required',
          'juridic_billing_last' => 'required|string',
          'juridic_billing_phone' => 'required|numeric',
          'juridic_billing_email' => 'required|email',
          'juridic_billing_company_name' => 'required|string',
          'juridic_billing_registration_code' => 'required|string',
          'juridic_billing_registration_number' => 'required|string',
          'juridic_billing_bank' => 'required|string',
          'juridic_billing_account' => 'required|string',
          'juridic_billing_address1' => 'required|string',
          'juridic_billing_country' => 'required|string',
          'juridic_billing_city' => 'required|string',
          'juridic_billing_zipcode' => 'required|string',
        ];
        if (!empty($this->juridic_billing_address2)) {
          $rules['juridic_billing_address2'] = 'string';
        }

        if (!empty($this->juridic_billing_county)) {
          $rules['juridic_billing_county'] = 'string';
        }
        if (!$this->juridic_identic) {
          // Include shipping address rules only if individual_identic is false
          $shippingRules = [
            'juridic_shipping_first' => 'required|string',
            'juridic_shipping_last' => 'required|string',
            'juridic_shipping_phone' => 'required|numeric',
            'juridic_shipping_email' => 'required|email',
            'juridic_shipping_address1' => 'required|string',
            'juridic_shipping_country' => 'required|string',
            'juridic_shipping_city' => 'required|string',
            'juridic_shipping_zipcode' => 'required|string',
          ];

          // Address line 2 and county are optional for shipping address too
          if (!empty($this->juridic_shipping_address2)) {
            $shippingRules['juridic_shipping_address2'] = 'string';
          }

          if (!empty($this->juridic_shipping_county)) {
            $shippingRules['juridic_shipping_county'] = 'string';
          }

          // Merge shipping address rules with existing rules
          $rules = array_merge($rules, $shippingRules);
        }
        $this->validate($rules);
      }
    }
  }
}
