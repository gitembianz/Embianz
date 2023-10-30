<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Status;
use App\Models\Account;
use App\Models\Address;
use Livewire\Component;
use App\Models\Cart_Item;
use App\Models\Order_Item;
use App\Models\Payment;

class StoreOrder extends Component
{
  public $step = 1;
  public $individual = true;
  public $juridic = false;
  public $individual_identic;
  public $juridic_identic;
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
  public $card = true;
  public $rtc = false;
  public $invoice = false;
  public $payments;
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
    $this->payments = Payment::get();
    $this->session_id = $_COOKIE['sessionId'];
    $closedStatusId = Status::where('name', 'closed')->where('type', 'cart')->first()->id;
    $this->cart = Cart::where('session_id', $this->session_id)->where('status_id', '!=', $closedStatusId)->latest()->first();
    if (!$this->cart) {
      $this->back = true;
    }
    $this->resetForm();
    $this->step = 1;
    $this->individual_identic = true;
    $this->juridic_identic = true;
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
    if ($this->cart) {
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
    $statusId = Status::where('name', 'new')->where('type', 'order')->first()->id;

    Order::create([
      'name' => $uniqueName,
      'session_id' => $this->session_id,
      'account_id' => $account->id,
      'cart_id' => $this->cart->id,
      'quantity_amount' => $this->cart->quantity_amount,
      'sum_amount' => $this->cart->final_amount,
      'currency_id' => $this->cart->currency_id,
      'status_id' =>  $statusId,
      'delivery_method' => 'cash on delivery',
    ]);
    $cartitems = Cart_Item::where('cart_id', $this->cart->id)->get();
    if ($cartitems) {
      $order = Order::where('cart_id', $this->cart->id)->first();
      foreach ($cartitems as $item) {
        Order_Item::create([
          'order_id' => $order->id,
          'product_id' => $item->product_id,
          'price' => $item->price,
          'quantity' => $item->quantity,
        ]);
      }
      $this->cart->order_id = $order->id;
    }
    $newStatusId = Status::where('name', 'closed')->where('type', 'cart')->first()->id;
    $this->cart->status_id = $newStatusId;
    $this->cart->save();
    $this->step++;
    $this->emit('cartUpdated');
  }
  public function next()
  {
    if ($this->cart) {
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
    session()->flash('notification', [
      'message' => 'All data ok!',
      'type' => 'warning',
      'title' => 'Blea e pizdet'
    ]);
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
  public function togglepayment($item)
  {
    if ($item == 'card') {

      $this->card = true;
      $this->rtc = false;
      $this->invoice = false;
    }
    if ($item == 'rtc') {
      $this->card = false;
      $this->rtc = true;
      $this->invoice = false;
    }
    if ($item == 'invoice') {
      $this->card = false;
      $this->rtc = false;
      $this->invoice = true;
    }
  }
}
