<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;

class StoreHeader extends Component
{
  public $limit = 5;
  public $search = '';
  public $active = false;

  public function render()
  {
    return view('livewire.store-header', [
      'categories' => $this->categories,
      'objects' => $this->objects
    ]);
  }
  public function close()
  {
    $this->active = false;
    $this->search = '';
  }
  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->limit($this->limit)->get();
  }
  public function getCategoriesQueryProperty()
  {
    return Category::orderBy('store_tab', 'desc')->with('subcategory');
  }
  public function getObjectsProperty()
  {
    return $this->objectsQuery->limit($this->limit)->get();
  }
  public function getObjectsQueryProperty()
  {
    return Product::name($this->search)->with('product_prices')->with('product_categories');
  }
}
