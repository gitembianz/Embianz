<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Products_categories;

class RelatedProductCategory extends Component
{

  use WithPagination;
  //related delclaration/
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelatedprod = false;
  public $categoryId;
  public $col = false;
  public $all = false;
  public $productidbeingremoved = null;
  public $columns = ['Id', 'Short Description', 'Created At'];
  public $selectedColumns = [];
  public $category;

  //add declaration
  public $searchadd = '';
  public $orderByadd = 'id';
  public $orderAscadd = true;
  public $checkedadd = [];
  public $selectPageadd = false;
  public $selectAlladd = false;
  public $coladd = false;
  public $alladd = false;
  public $columnsadd = ['Id', 'Short Description', 'Created At'];
  public $selectedColumnsadd = [];
  public $productidbeinglink = null;
  public $showTable = false;
  public $totalRecords;
  public $loadAmount = 10;

  // function for add products
  public function toggleTable()
  {
    $this->showrelatedprod = true;
    $this->showTable = !$this->showTable;
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function cancel()
  {
    $this->showTable = false; // Set $showTable to false to hide the table
  }
  public function showColumnadd($column)
  {
    if ($column === 'Name') {
      return true;
    }
    return in_array($column, $this->selectedColumnsadd);
  }
  public function updatedSelectPageadd($value)
  {
    if ($value) {
      $this->checkedadd = $this->prodds->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checkedadd = [];
    }
  }
  public function swapSortDirectionadd()
  {
    return $this->orderAscadd === '1' ? '0' : '1';
  }
  public function isCheckedadd($id)
  {
    return in_array($id, $this->checked);
  }
  public function sortByadd($columnName)
  {

    if ($this->orderByadd === $columnName) {
      $this->orderAscadd = $this->swapSortDirectionadd();
    } else {
      $this->orderAscadd = '1';
    }

    $this->orderByadd = $columnName;
  }
  public function selectAlladd()
  {
    $this->selectAlladd = true;
    $this->checkedadd = $this->prodds->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getProddsProperty()
  {
    $ids = $this->relatedproducts->pluck('product_id')->toArray();
    $unrelated = Product::whereNotIn('id', $ids);
    if (!empty($this->searchadd)) {
      $unrelated->where('name', 'like', '%' . $this->searchadd . '%');
    }
    $unrelated->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
    if ($this->selectAlladd) {
      return $unrelated->get();
    } else {
      return $unrelated->limit($this->loadAmount)->get();
    }
  }
  public function confirmProductlink($productid)
  {
    $this->productidbeinglink = $productid;
    $this->dispatchBrowserEvent('show-link-modal');
  }
  public function linkSingleRecord()
  {
    $id = $this->productidbeinglink;
    $product = new  Products_categories();
    $product->product_id = $id;
    $product->category_id = $this->category->id;
    $product->save();
    $this->checkedadd = array_diff($this->checkedadd, [$id]);
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function linkRecords()
  {
    $products = Product::whereKey($this->checkedadd)->get();
    foreach ($products as $product) {
      $prodadd = new Products_categories();
      $prodadd->product_id = $product->id;
      $prodadd->category_id = $this->category->id;
      $prodadd->save();
    }
    $this->selectPageadd = false;
    $this->checkedadd = [];
    session()->flash('notification', [
      'message' => 'Records related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function updatedCheckedadd()
  {
    $this->selectPageadd = false;
  }

  //function for related products
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
      $this->checked = $this->relatedproducts->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
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
    $this->checked = $this->relatedproductsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function load()
  {
    $this->perPage += 10;
  }
  public function getRelatedproductsProperty()
  {
    return Products_categories::where('category_id', $this->category->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function confirmItemRemoval($productid)
  {
    $this->productidbeingremoved = $productid;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function deleteSingleRecord()
  {
    $id = $this->productidbeingremoved;
    $product = Products_categories::findOrFail($id);
    $product->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteRecords()
  {
    $products = Products_categories::whereKey($this->checked)->get();
    foreach ($products as $product) {
      $id = $product->id;
      $producttodel = Products_categories::find($id);
      $producttodel->delete();
    }

    $this->checked = [];
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemsRemoval()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function confirmLinkmultiple()
  {
    $this->dispatchBrowserEvent('show-link-modal-multiple');
  }
  public function render()
  {
    $relatedProducts = $this->relatedproducts
      ->where(function ($query) {
        $query->whereHas('product', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('short_description', 'LIKE', '%' . $this->search . '%');
        });
      })->get();

    if ($this->showTable === true) {
      return view('livewire.related-product-category', [
        'relatedproducts' => $relatedProducts,
        'prodds' => $this->prodds,
      ]);
    } else {
      return view('livewire.related-product-category', [
        'relatedproducts' => $relatedProducts,
      ]);
    }
  }
  public function mount(Category $category)
  {
    $this->category = $category;
    $this->selectedColumns = $this->columns;
    $this->selectedColumnsadd = $this->columnsadd;
  }
}
