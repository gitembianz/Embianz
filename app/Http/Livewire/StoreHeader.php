<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;

class StoreHeader extends Component
{
  public $session_id;
  protected $listeners = [
    'newcart' => 'NewCart',
    'orderprocess' => 'getCartProperty',
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

  public function render()
  {
    $data = [
      'categories' => $this->categories,
      'cart' => $this->cart,

    ];
    return view('livewire.store-header', $data);
  }
  public function mount()
  {
    $this->session_id = $this->getSessionId();
  }

  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->latest()
      ->first() ?? null;
  }

  public function NewCart()
  {
    $this->getCategoriesProperty();
    $this->emit('newcartlist');
  }

  public function getCategoriesProperty()
  {
    return app()->make('cached_categories')->where('store_tab', 1)
      ->where('has_parrent', 0)->sortBy('sequence')->take(app('global_limit_category'));
  }
}
