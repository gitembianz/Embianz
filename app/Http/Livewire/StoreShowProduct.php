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
  public function mount($productId)
  {
    $this->productId = $productId;
  }

  public function getProductProperty()
  {
    return Product::where('id', $this->productId)->with([
      'media' => function ($query) {
        $query->whereIn('type', ['full', 'original'])
          ->orderBy('sequence');
      }
    ])->first();
  }
}
