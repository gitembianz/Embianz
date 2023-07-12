<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class StoreProducts extends Component
{

  public $totalRecords;
  public $loadAmount = 9;

  public function loadMore()
  {
    $this->loadAmount += 10;
  }

  public function mount()
  {
    $this->totalRecords = Product::count();
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
    return Product::orderBy('created_at', 'desc');
  }
}
