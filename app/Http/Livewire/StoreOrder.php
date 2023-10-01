<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StoreOrder extends Component
{
  public $step = 1;
  public $individual = false;
  public $juridic = false;
  //delratation individual person
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
  public $individual_identic = true;
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
  public $individual_shipping_identic;

  public function render()
  {
    return view('livewire.store-order');
  }
  public function mount()
  {
    $this->individual_identic = true;
  }
  public function showindividual()
  {
    $this->juridic = false;
    $this->individual = true;
  }
  public function showjuridic()
  {
    $this->individual = false;
    $this->juridic = true;
  }
  public function next()
  {
    $this->step++;
  }
  public function previous()
  {
    $this->step--;
  }
}
