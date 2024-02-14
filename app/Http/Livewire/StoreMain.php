<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class StoreMain extends Component
{
  public $quantity;

  public function getSliderItemsProperty()
  {
    return Category::select('id', 'slider_sequence')->where('slider_sequence', '!=', '0')->with(['media' => function ($query) {
      $query->select('path', 'name')->where('type', 'original');
    }])->orderby('sequence')->get();
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
        $query->select('product_id');
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
    $this->quantity = app('global_low_stock');
  }
}