<?php

namespace App\Http\Livewire;


use App\Models\Product;
use Livewire\Component;


class StoreShowProduct extends Component
{
  public $productId;
  public $quantity;
  public $session_id;

  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product
    ]);
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
  public function mount($productId)
  {
    $this->productId = $productId;
    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
  }

  public function getProductProperty()
  {
    return Product::select('id', 'name')
      ->with([
        'media' => function ($query) {
          $query->select('name', 'path', 'type', 'sequence')
            ->whereIn('type', ['full', 'original'])
            ->orderBy('sequence');
        },
        'related_product' => function ($query) {
          $query->select('parrent_id', 'product_id', 'id')->with([
            'product' => function ($query) {
              $query->select('id', 'name', 'seo_id', 'quantity')->with([
                'media' => function ($query) {
                  $query->select('path', 'name')->where('type', 'main');
                },
                'product_prices' => function ($query) {
                  $query->select('product_id', 'value', 'pricelist_id', 'discount', 'rrp_value')
                    ->with(['pricelist' => function ($query) {
                      $query->select('id', 'currency_id')->with('currency:id,name');
                    }]);
                },
                'wishlists' => function ($query) {
                  $query->select('id', 'product_id')->where('session_id', $this->session_id);
                }
              ]);
            }
          ]);
        }
      ])->where('id', $this->productId)->first();
  }
}
