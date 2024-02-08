<?php

namespace App\Http\Livewire;


use App\Models\Product;
use Livewire\Component;


class StoreShowProduct extends Component
{
  public $productId;
  public $quantity = 10;

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
    return Product::with([
      'media' => function ($query) {
        $query->whereIn('type', ['full', 'original'])->orderBy('sequence');
      },
      'related_product.product' => function ($query) {
        $query->with([
          'media' => function ($query) {
            $query->where('type', 'main');
          },
          'product_prices' => function ($query) {
            $query->with('pricelist.currency');
          },
          'wishlists'
        ]);
      },
    ])->where('id', $this->productId)->first();
  }
}
