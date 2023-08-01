<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class StoreHeader extends Component
{
  public $limit = 5;

  public function render()
  {
    return view('livewire.store-header', [
      'categories' => $this->categories
    ]);
  }
  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->limit($this->limit)->get();
  }
  public function getCategoriesQueryProperty()
  {
    return Category::orderBy('store_tab', 'desc')->with('subcategory');
  }
}
