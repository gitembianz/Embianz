<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount = 9;
  public $search = "";
  public $quantity = 20;

  public function loadMore()
  {
    $this->loadAmount += 10;
  }

  public function render()
  {
    return view('livewire.store-products', [
      'products' => $this->products
    ]);
  }
  public function getProductsProperty()
  {
    return $this->productsQuery->limit($this->loadAmount)->get();
  }
  public function getProductsQueryProperty()
  {
    return Product::name($this->search)->orderBy('created_at', 'desc');
  }
}
