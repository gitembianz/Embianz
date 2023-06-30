<?php

namespace App\Http\Livewire;

use App\Models\Product_Spec;
use App\Models\Specs;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedSpecProduct extends Component
{

  use WithPagination;
  //related delclaration
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelatedspecs = false;
  public $productId;
  public $col = false;
  public $all = false;
  public $columns = ['Id', 'Unit', 'Value', 'Created At'];
  public $selectedColumns = [];
  public $specidbeingremoved = null;
  public $addrelatedspecs = false;
  //Add specs declaration
  public $perPageadd = 10;
  public $searchadd = '';
  public $orderByadd = 'id';
  public $orderAscadd = true;
  public $checkedadd = [];
  public $selectPageadd = false;
  public $selectAlladd = false;
  public $columnsadd = ['Id', 'Unit', 'Group', 'Value', 'Created At'];
  public $selectedColumnsadd = [];
  public $coladd = false;
  public $alladd = false;
  public $islink = false;
  public $rowindex = null;
  public $spec = [];

  public function render()
  {
    return view('livewire.related-spec-product', [
      'relatedspecs' => $this->relatedspecs,
      'addspecs' => $this->addspecs,
    ]);
  }
  public function mount($productId)
  {
    $this->productId = $productId;
    $this->selectedColumns = $this->columns;
    $this->selectedColumnsadd = $this->columnsadd;
  }
  //function for realted
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
      $this->checked = $this->relatedspecs->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedspecsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getRelatedspecsProperty()
  {
    return $this->relatedspecsQuery->paginate($this->perPage, ['*'], 'related');
  }
  public function getRelatedspecsQueryProperty()
  {
    return Product_Spec::where('product_id', $this->productId)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('spec');
  }
  public function confirmRemoval($specid)
  {
    $this->specidbeingremoved = $specid;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function deleteSingleRecord()
  {
    $id = $this->specidbeingremoved;
    $item = Product_Spec::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('message', 'Record deleted Successfully');
  }
  public function deleteRecords()
  {
    $items = Product_Spec::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemtodel = Product_Spec::find($id);
      $itemtodel->delete();
    }
    $this->checked = [];
    session()->flash('message', 'Related records deleted succesfuly');
  }
  public function confirmRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  // add specs function
  public function addrelated()
  {
    $this->showrelatedspecs = true;
    $this->addrelatedspecs = true;
  }
  public function setlink($index)
  {
    $this->islink = true;
    $this->rowindex = $index;
  }
  public function savespecs()
  {
  }
  public function cancellink()
  {
    $this->rowindex = null;
    $this->islink = false;
  }
  public function closemodal()
  {
    $this->addrelatedspecs = false;
    $this->rowindex = null;
    $this->islink = false;
    $this->checkedadd = [];
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
      $this->checkedadd = $this->addspecs->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    return in_array($id, $this->checkedadd);
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
    $this->checkedadd = $this->addspecsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getAddspecsProperty()
  {
    return $this->addspecsQuery->paginate($this->perPageadd, ['*'],  'specs');
  }
  public function getAddspecsQueryProperty()
  {
    return Specs::search($this->searchadd)->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
  }
  public function updatedCheckedadd()
  {
    $this->selectPageadd = false;
  }
}
