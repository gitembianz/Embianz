<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class StoreMain extends Component
{
  public $limit = 10;
  public $quantity = 10;

  public function getSliderItemsProperty()
  {
    return Category::where('slider_sequence', '!=', '0')->with('media')->orderby('sequence')->get();
  }

  public function getPopProductsProperty()
  {
    return Product::where('active', true)
      ->orderBy('popularity', 'desc')
      ->with([
        'media' => function ($query) {
          $query->where('type', 'main');
        },
        'product_prices' => function ($query) {
          $query->with('pricelist.currency');
        },
        'wishlists'
      ])
      ->limit($this->limit)
      ->get();
  }

  public function render()
  {
    return view('livewire.store-main', [
      'popproducts' => $this->popproducts,
      'slideritems' => $this->slideritems,

    ]);
  }
}
