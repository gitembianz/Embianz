<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Products_categories;
use Livewire\Component;
use Livewire\WithPagination;

class Productstable extends Component
{

  use WithPagination;

  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
    public $selectAll = false;

  public function render()
  {
    return view('livewire.productstable', [
      'products' => Product::search($this->search)
        ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
        ->simplePaginate($this->perPage),
      'categories' => Products_categories::all()
    ]);
  }

  public function deleteRecords()
  {

    $products = Product::whereKey($this->checked)->get();

    foreach ($products as $product) {
      $id = $product->id;
      $producttodel = Product::find($id);
      $productcat = Products_categories::where('product_id', $id)->first();
      if ($productcat != NULL) {
        $productcat->delete();
      }


      $producttodel->delete();
    }

    $this->checked = [];
    session()->flash('info', 'Selected product deleted succesfuly');
  }

  public function deleteSingleRecord($id)
  {
    $product = Product::findOrFail($id);
    $productcat = Products_categories::where('product_id', $id)->first();
    if ($productcat != NULL) {
      $productcat->delete();
    }
    $product->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('info', 'Record deleted Successfully');
  }

  public function isChecked($id)
  {
      return in_array($id, $this->checked);
  }

}
