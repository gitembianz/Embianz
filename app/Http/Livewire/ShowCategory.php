<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class ShowCategory extends Component
{

  public $categoryId;
  public $category;

  public function mount($categoryId)
  {
      $this->categoryId = $categoryId;
      $this->category = Category::find($categoryId);
  }

  public function render()
  {
      return view('livewire.show-category');
  }
}
