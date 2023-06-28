<?php

namespace App\Http\Livewire;

use App\Exports\CategoriesExport;
use App\Models\Category;
use App\Models\Products_categories;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedCategoryProduct extends Component
{

  use WithPagination;
  public $showTable = false;
  public $productId;

  //related variables
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelatedcat = false;
  public $col = false;
  public $all = false;
  public $catidbeingremoved = null;
  public $columns = ['Id', 'Short Description', 'Created At'];
  public $selectedColumns = [];

  //add variables
  public $perPageadd = 10;
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
  public $catidbeinglink = null;

//add new functions
  public function toggleTable()
  {
      $this->showTable = !$this->showTable;
  }
  public function cancel()
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
      $this->checkedadd = $this->cats->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checkedadd = $this->catsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getCatsProperty()
  {
    return $this->catsQuery->paginate($this->perPageadd,['*'],  'categories');
  }
  public function getCatsQueryProperty()
  {
    return Category::search($this->searchadd)->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
  }
  public function confirmItemlink($itemtid)
  {
    $this->catidbeinglink = $itemtid;
    $this->dispatchBrowserEvent('show-link-modal');
  }
  public function linkSingleRecord()
  {
    $id = $this->catidbeinglink;
    $item = new  Products_categories();
    $item->product_id = $this->productId;
    $item->category_id = $id;
    $item->save();
    $this->checkedadd = array_diff($this->checkedadd, [$id]);
    session()->flash('message', 'Record related Successfully');
  }
  public function confirmItemsLinkmultiple()
  {
    $this->dispatchBrowserEvent('show-link-modal-multiple');
  }
  public function linkRecords()
  {
    $items = Category::whereKey($this->checkedadd)->get();
    foreach ($items as $item) {
      $itemadd = new Products_categories();
      $itemadd->product_id = $this->productId;
      $itemadd->category_id = $item->id;
      $itemadd->save();
    }
    $this->checkedadd = [];
    session()->flash('message', 'Categories related succesfuly');
  }
  public function updatedCheckedadd()
  {
    $this->selectPageadd = false;
  }

//Related item function
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
      $this->checked = $this->relatecats->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedcatsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }

  public function getRelatedcatsProperty()
  {
    return $this->relatedcatsQuery->paginate($this->perPage,['*'], 'related');
  }
  public function getRelatedcatsQueryProperty()
  {
    return Products_categories::where('product_id', $this->productId)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('category');
  }
  public function confirmItemRemoval($itemid)
  {
    $this->catidbeingremoved = $itemid;
    $this->dispatchBrowserEvent('show-delete-modal');
  }

  public function deleteSingleRecord()
  {
    $id = $this->catidbeingremoved;
    $item = Products_categories::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('message', 'Record deleted Successfully');
  }
  public function confirmItemsRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function deleteRecords()
  {
    $items = Products_categories::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemtodel = Products_categories::find($id);
      $itemtodel->delete();
    }
    $this->checked = [];
    session()->flash('message', 'Categories deleted succesfuly');
  }
  public function exportSelected()
  {
    $export = new CategoriesExport($this->checked);
    $this->checked = [];
    $this->selectPage = false;
    return $export->download('categories.xlsx');
  }

  //render function

  public function mount($productId)
  {
    $this->productId = $productId;
    $this->selectedColumns = $this->columns;
    $this->selectedColumnsadd = $this->columnsadd;
  }

  public function render()
  {
    if($this->showTable === true){
      return view('livewire.related-category-product', [
        'relatedcats' => $this->relatedcats,
        'cats' =>$this->cats
      ]);
    }else{
      return view('livewire.related-category-product', [
        'relatedcats' => $this->relatedcats
      ]);
    }


  }
}
