<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class StoreMain extends Component
{
  public $limit = 10;
  public $slider;

  public function mount($slider_category)
  {
    $this->slider = $slider_category;
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
  public function getCategoryProperty()
  {
    return Category::find($this->slider)->first()->with('product_categories');
  }
}
