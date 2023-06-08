<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\ProductsExport;
use App\Models\Products_categories;

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
      'products' => $this->products,
      'categories' => Products_categories::all()
    ]);
  }

  public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->products->pluck('id')->map(fn ($item) => (string) $item)->toArray();
        } else {
            $this->checked = [];
        }
    }

    public function updatedChecked()
    {
        $this->selectPage = false;
    }


    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = Product::all()->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function getStudentsProperty()
    {
        return $this->productsQuery;;
    }

    public function getProductsProperty(){
      return Product::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
      ->paginate($this->perPage);
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

  public function exportSelected()
  {

      $export = new ProductsExport($this->checked);

      $this->checked = [];
      $this->selectPage = false;
      return $export->download('products.xlsx');
  }

}
