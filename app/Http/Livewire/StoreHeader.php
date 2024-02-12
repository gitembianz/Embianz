<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

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
    return Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->latest()->first() ?? [];
  }

  public function NewCart()
  {
    $this->getCategoriesProperty();
    $this->emit('newcartlist');
  }

  public function getCategoriesProperty()
  {
    return Category::where('active', 1)
      ->where('store_tab', '1')
      ->where('has_parrent', '0')
      ->with([
        'subcategory' => function ($query) {
          $query->with('category', function ($subQuery) {

            $subQuery->where('store_tab', 1)->where('active', 1);
          })->with([
            'category.media' => function ($query) {
              $query->where('type', 'min'); // Filter and limit the media relationship
            },
            'category.subcategory',
            'category.subcategory.category.media' => function ($query) {
              $query->where('type', 'min'); // Filter and limit the media relationship
            },
          ]);
        },
        'media' => function ($query) {
          $query->where('type', 'min'); // Filter and limit the media relationship
        }
      ])->limit(app('global_limit_category'))->orderby('sequence')->get();
  }
}
