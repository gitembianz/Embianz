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
    return Category::where('slider_sequence', '!=', '0')->with(['media' => function ($query) {
      $query->select('path', 'name')->where('type', 'original');
    }])->orderby('sequence')->get();
  }

  public function getPopProductsProperty()
  {
    return Product::select('id', 'name', 'seo_id', 'quantity', 'short_description')
      ->where('active', true)
      ->orderBy('popularity', 'desc')
      ->with([
        'media' => function ($query) {
          $query->select('path', 'name')->where('type', 'main');
        },
        'product_prices' => function ($query) {
          $query->with('pricelist.currency');
        },
        'wishlists'
      ])
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
