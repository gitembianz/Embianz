<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store_Settings;
use Livewire\Component;

class StoreMain extends Component
{
  public $limit = 10;
  public $slider;
  public $category;

  public function mount()
  {
    $sliderCategory = Store_Settings::where('parameter', 'slider_category')->first();

    if ($sliderCategory) {
      $categoryId = $sliderCategory->value;
      $this->category = Category::find($categoryId);
    } else {
      $this->category = null;
    }
  }
  public function render()
  {
    return view('livewire.store-main', [
      'popproducts' => $this->popproducts,
      'category' => $this->category
    ]);
  }
  public function getPopProductsProperty()
  {
    return $this->popproductsQuery->limit($this->limit)->get();
  }
  public function getPopProductsQueryProperty()
  {
    return Product::orderBy('popularity', 'desc')->with('media')->with('product_prices');
  }
}
