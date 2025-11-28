<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;

class CartQuantity extends Component
{
  public $session_id;

  protected $listeners = [
    'cartUpdated' => 'mount',
    'newcart' => 'mount',
    'orderprocess' => 'mount'

  ];

  public function mount()
  {
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();
  }

  public function render()
  {
    return view('livewire.cart-quantity', ['quantity' => $this->quantity]);
  }
 public function getQuantityProperty()
{
    $quantity = Cart::where('session_id', $this->session_id)
        ->where('status_id', '!=', app('global_statuses')['cart_closed'])
        ->sum('quantity_amount');

    return $quantity > 0 ? $quantity : null;
}

}
