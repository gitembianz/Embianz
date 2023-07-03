<?php

namespace App\Http\Livewire;

use App\Models\Product;
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
  public $searchadd = '';
  public $orderByadd = 'updated_at';
  public $orderAscadd = 'desc';
  public $spec = [];
  public $item;
  public $itemselected = null;
  public $specid;
  public $allow = false;
  public $value = false;


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
    $this->item = Product::find($productId);
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
  public function select($id)
  {
    $this->itemselected = Specs::find($id)->name;
    $this->specid = $id;
    $this->allow = false;
    $this->value = true;
  }
  public function allowselect()
  {
    $this->allow = true;
  }
  public function closemodal()
  {
    $this->addrelatedspecs = false;
    $this->allow = false;
  }
  public function savespecs()
  {
    $val = $this->spec;
    if (array_key_exists('value', $val)) {
      $newspec = new Product_Spec();
      $newspec->product_id = $this->productId;
      $newspec->spec_id = $this->specid;
      $newspec->value = $val['value'];
      $newspec->save();
      $this->addrelatedspecs = false;
      $this->allow = false;
      $this->specid = null;
      $this->value = false;
      session()->flash('message', 'Spec related succesfuly succesfuly');
    } else {
      session()->flash('message', 'Please provide a value!');
    }
  }
  public function getAddspecsProperty()
  {
    return $this->addspecsQuery->get();
  }
  public function getAddspecsQueryProperty()
  {
    return Specs::search($this->searchadd)->orderBy($this->orderByadd, $this->orderAscadd);
  }
}
