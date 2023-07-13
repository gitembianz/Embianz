<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class StoreShowProduct extends Component
{

  public $productId;

  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product
    ]);
  }

  public function getProductProperty()
  {
    return $this->productQuery;
  }
  public function getProductQueryProperty()
  {
    return Product::find($this->productId);
  }
}
