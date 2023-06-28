<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class RelatedCategoryProduct extends Component
{

  use WithPagination;

    public function render()
    {
        return view('livewire.related-category-product');
    }
}
