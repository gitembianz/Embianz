<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class ShowProduct extends Component
{

  public $productId;
  public $product;

  public function mount($productId)
  {
      $this->productId = $productId;
      $this->product = Product::find($productId);
  }

    public function render()
    {
        return view('livewire.show-product');
    }
}
