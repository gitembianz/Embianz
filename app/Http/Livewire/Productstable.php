<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class Productstable extends Component
{

  use WithPagination;

  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;

    public function render()
    {
        return view('livewire.productstable', [
          'products' => Product::search($this->search)
          ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
          ->simplePaginate($this->perPage),
      ]);
    }
}
