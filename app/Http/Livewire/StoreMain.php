<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StoreMain extends Component
{
  public $quantity;
  public $session_id;

  public function getSliderItemsProperty()
  {
    return app()->make('cached_categories')->where('slider_sequence', '!=', '0')->sortBy('slider_sequence');
  }

  private function getSessionId()
  {
    if (array_key_exists('sessionId', $_COOKIE)) {
      return $_COOKIE['sessionId'];
    } else {
      return session()->getId();
    }
  }

  public function getPopProductsProperty()
  {
    return app()->make('cached_products')->filter(function ($product) {
      return $product->type != 'parrent';
    })->sortByDesc('popularity')->take(app('global_limit_slideritems'));
  }

  public function getNewProductsProperty()
  {
    return app()->make('cached_products')
      ->filter(function ($product) {
        return $product->type != 'parrent' && $product->is_new == true;
      })
      ->sortByDesc('popularity')
      ->take(app('global_limit_slideritems'));
  }



  public function render()
  {
    return view('livewire.store-main', [
      'popproducts' => $this->popproducts,
      'newproducts' => $this->newproducts,

      'slideritems' => $this->slideritems,

    ]);
  }
  public function mount()
  {
    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
  }
}
