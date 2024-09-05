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
  public $showTable = false;

  //related variables
  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelateitems = false;
  public $idbeingremoved = null;
  public $columns = ['Id', 'Category Name', 'Product Name', 'Product Description', 'Product type', 'Product is active?', 'Created At', 'Updated At'];
  public $selectedColumns = [];
  public $category;

  //add variables
  public $searchadd = '';
  public $checkedadd = [];
  public $selectPageadd = false;
  public $selectAlladd = false;
  public $idbeinglink = null;

  public $linksingle = false;
  public $linkmultiple = false;
  public $single = false;
  public $multiple = false;
  public $rind2 = null;
  public $rind = null;

  public function expandRow2($index)
  {
    if ($this->rind2  === null) {
      $this->rind2 = $index;
    } elseif ($this->rind2 != $index) {
      $this->rind2 = $index;
    } else {
      $this->rind2 = null;
    }
  }
  public function expandRow($index)
  {
    if ($this->rind  === null) {
      $this->rind = $index;
    } elseif ($this->rind != $index) {
      $this->rind = $index;
    } else {
      $this->rind = null;
    }
  }
  public function confirmItemRemoval($id)
  {
    $this->idbeingremoved = $id;
    $this->single = true;
  }
  public function confirmItemsRemoval()
  {
    $this->multiple = true;
  }
  public function cancel_delete()
  {
    $this->multiple = false;
    $this->single = false;
  }

  public function cancel_link()
  {
    $this->linkmultiple = false;
    $this->linksingle = false;
  }
  // function for add products
  public function addrelated()
  {
    $this->showrelateitems = true;
    $this->showTable = true;
  }
  public function confirmItemLink($id)
  {
    $this->idbeinglink = $id;
    $this->linksingle = true;
  }
  public function confirmItemsLink()
  {
    $this->linkmultiple = true;
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function closemodal()
  {
    $this->showTable = false;
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
  public function updatedCheckedadd()
  {
    $this->selectPageadd = false;
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

    return $unrelated->paginate($this->loadAmount);
  }

  public function linkSingleRecord()
  {
    $id = $this->idbeinglink;
    $product = new  Products_categories();
    $product->product_id = $id;
    $product->category_id = $this->category->id;
    $product->save();
    $this->linksingle = false;

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
    $this->linkmultiple = false;

    session()->flash('notification', [
      'message' => 'Records related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
    $this->checked = $this->RelatedProducts->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getRelatedProductsQueryProperty()
  {
    return $this->relatedPoductsQuery->get();
  }
  public function getRelatedProductsProperty()
  {
    return Products_categories::where('category_id', $this->category->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }


  public function deleteSingleRecord()
  {
    $id = $this->idbeingremoved;
    $product = Products_categories::findOrFail($id);
    $product->delete();
    $this->checked = array_diff($this->checked, [$id]);
    $this->single = false;

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
    $this->multiple = false;

    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function render()
  {
    $relatedproducts = $this->RelatedProducts
      ->where(function ($query) {
        $query->whereHas('product', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('short_description', 'LIKE', '%' . $this->search . '%')
            ->orWhere('type', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);

    return view('livewire.related-product-category', [
      'relatedproducts' => $relatedproducts,
      'prodds' => $this->prodds,
    ]);
  }
  public function mount(Category $category)
  {
    $this->category = $category;
    $this->selectedColumns = $this->columns;
  }
}