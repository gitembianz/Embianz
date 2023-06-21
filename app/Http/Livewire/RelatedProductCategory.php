<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Products_categories;

class RelatedProductCategory extends Component
{

  use WithPagination;
  public $showrelatedprod = false;
  public $categoryId;
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $columns = ['Id', 'Short Description', 'Created At'];
  public $selectedColumns = [];
  public $products_related = [];
  public $products =[];


  public function mount($categoryId)
  {
    $this->categoryId = $categoryId;
    $this->selectedColumns = $this->columns;
    $this->products_related = Products_categories::where('category_id', $this->categoryId)->get();
  }

  public function showColumn($column)
  {
    if ($column === 'Name') {
      return true;
    }
    return in_array($column, $this->selectedColumns);
  }

  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->files->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }

  public function updatedChecked()
  {
    $this->selectPage = false;
  }

  public function sortBy($columnName)
  {

    if ($this->orderBy === $columnName) {
      $this->orderAsc = $this->swapSortDirection();
    } else {
      $this->orderAsc = '1';
    }

    $this->orderBy = $columnName;
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->filesQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }

  public function getProductsProperty()
  {
    return $this->productsQuery->paginate($this->perPage);
  }

  public function getProductsQueryProperty()
  {
    return Products_categories::search($this->search)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->where('item_id', $this->categoryId)->where('tabel_id', $this->type)->with('location');
  }

  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }

  public function getproducts()
  {
  $this->products = Product::all();
  // dd($this->products);
  }

  public function render()
  {
    return view('livewire.related-product-category', [
      'products_related' => $this->products_related
    ]);
  }
}
