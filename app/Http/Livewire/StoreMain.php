<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class StoreMain extends Component
{
  public $quantity;
  public $session_id;

  public function getSliderItemsProperty()
  {
    return Category::select('id', 'slider_sequence')->where('slider_sequence', '!=', '0')->with(['media' => function ($query) {
      $query->select('path', 'name', 'sequence')->where('type', 'original');
    }])->orderby('sequence')->get();
  }

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

  public function getPopProductsProperty()
  {
    return Product::with([
      'media' => function ($query) {
        $query->select('path', 'name')->where('type', 'main');
      },
      'product_prices' => function ($query) {
        $query->select('product_id', 'value', 'discount', 'rrp_value', 'pricelist_id')
          ->with(['pricelist' => function ($query) {
            $query->select('id', 'currency_id')->with('currency:id,name');
          }]);
      },
      'wishlists' => function ($query) {
        $query->select('id', 'product_id')->where('session_id', $this->session_id);
      }
    ])
      ->select('id', 'name', 'seo_id', 'quantity', 'short_description', 'popularity')
      ->where('active', true)
      ->orderBy('popularity', 'desc')
      ->limit(app('global_limit_slideritems'))
      ->get();
  }



  public function render()
  {
    return view('livewire.store-main', [
      'popproducts' => $this->popproducts,
      'slideritems' => $this->slideritems,

    ]);
  }
  public function mount()
  {
    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
  }
}
