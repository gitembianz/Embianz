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
    if ($this->search != '') {
      return view('livewire.store-header', [
        'categories' => $this->categories,
        'objects' => $this->objects,
        'cats' => $this->cats
      ]);
    } else {
      return view('livewire.store-header', [
        'categories' => $this->categories
      ]);
    }
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
    return $this->objectsQuery->get();
  }
  public function getObjectsQueryProperty()
  {
    return Product::name($this->search)->with('product_prices')->with('media');
  }
  public function getCatsProperty()
  {
    return $this->catsQuery->get();
  }
  public function getCatsQueryProperty()
  {
    return Category::name($this->search)->with('media');
  }
}
