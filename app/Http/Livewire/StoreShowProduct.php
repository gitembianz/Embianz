<?php

namespace App\Http\Livewire;


use App\Models\Product;
use Livewire\Component;


class StoreShowProduct extends Component
{
  public $productId;
  public $quantity;

  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product
    ]);
  }
  public function mount($productId)
  {
    $this->productId = $productId;
    $this->quantity = app('global_low_stock');
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
        ])->take(app('global_limit_slideritems'));
      },
    ])->where('id', $this->productId)->first();
  }
}
