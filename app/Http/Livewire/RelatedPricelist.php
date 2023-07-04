<?php

namespace App\Http\Livewire;

use App\Models\PriceList;
use App\Models\PricelistEntries;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedPricelist extends Component
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
  public $showrelatedprice = false;
  public $productId;
  public $col = false;
  public $all = false;
  public $columns = ['Id', 'Currency', 'Value', 'Created At'];
  public $selectedColumns = [];
  public $priceidbeingremoved = null;
  public $addrelatedprice = false;
  //Add specs declaration
  public $searchadd = '';
  public $orderByadd = 'updated_at';
  public $orderAscadd = 'desc';
  public $price = [];
  public $item;
  public $itemselected = null;
  public $priceid;
  public $allow = false;
  public $update = false;

  public function render()
  {
    return view('livewire.related-pricelist', [
      'relatedprices' => $this->relatedprices,
      'addprices' => $this->addprices,
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
      $this->checked = $this->relatedprices->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedpricesQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getRelatedpricesProperty()
  {
    return $this->relatedpricesQuery->paginate($this->perPage);
  }
  public function getRelatedpricesQueryProperty()
  {
    return PricelistEntries::where('product_id', $this->productId)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('pricelist');
  }
  public function confirmRemoval($id)
  {
    $this->priceidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function deleteSingleRecord()
  {
    $id = $this->priceidbeingremoved;
    $item = PricelistEntries::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('message', 'Record deleted Successfully');
  }
  public function deleteRecords()
  {
    $items = PricelistEntries::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemtodel = PricelistEntries::find($id);
      $itemtodel->delete();
    }
    $this->checked = [];
    session()->flash('message', 'Related records deleted succesfuly');
  }
  public function confirmRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function edititem($id, $idspec)
  {
    $this->update = true;
    $this->addrelatedprice = true;
    $this->itemselected = PriceList::find($idspec)->name;
    $this->priceid = $id;
  }
  public function confirmitem()
  {
    $val = $this->price;
    if (array_key_exists('value', $val)) {
      $new = PricelistEntries::find($this->priceid);
      $new->value = $val['value'];
      $new->save();
      $this->addrelatedprice = false;
      $this->allow = false;
      $this->priceid = null;
      $this->price = [];
      $this->itemselected = null;
      $this->search = '';
      $this->update = false;
      session()->flash('message', 'Spec related succesfuly succesfuly');
    } else {
      session()->flash('message', 'Please provide a value!');
    }
  }

  //function for add new pricelist
  public function addrelated()
  {
    $this->showrelatedprice = true;
    $this->addrelatedprice = true;
  }
  public function select($id)
  {
    $this->itemselected = PriceList::find($id)->name;
    $this->priceid = $id;
    $this->allow = false;
  }
  public function allowselect()
  {
    $this->allow = true;
  }
  public function closemodal()
  {
    $this->addrelatedprice = false;
    $this->allow = false;
    $this->itemselected = null;
    $this->update = false;
  }
  public function saveitem()
  {
    $val = $this->price;
    if (array_key_exists('value', $val)) {
      $new = new PricelistEntries();
      $new->product_id = $this->productId;
      $new->pricelist_id = $this->priceid;
      $new->value = $val['value'];
      $new->save();
      $this->addrelatedprice = false;
      $this->allow = false;
      $this->priceid = null;
      $this->price = [];
      $this->itemselected = null;
      $this->search = '';
      session()->flash('message', 'Price related succesfuly succesfuly');
    } else {
      session()->flash('message', 'Please provide a value!');
    }
  }
  public function getAddpricesProperty()
  {
    return $this->addpricesQuery->get();
  }
  public function getAddpricesQueryProperty()
  {
    return PriceList::search($this->searchadd)->orderBy($this->orderByadd, $this->orderAscadd)->with('currency');
  }
}
