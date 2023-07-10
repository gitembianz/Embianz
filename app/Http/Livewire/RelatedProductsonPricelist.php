<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\PriceList;
use Livewire\WithPagination;
use App\Models\PricelistEntries;

class RelatedProductsonPricelist extends Component
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
  public $showrelatedprods = false;
  public $priceId;
  public $col = false;
  public $all = false;
  public $columns = ['Id', 'Currency', 'Value', 'Created At'];
  public $selectedColumns = [];
  public $idtodel = null;
  public $addrelatedproducts  = false;

  //Add specs declaration
  public $searchadd = '';
  public $orderByadd = 'updated_at';
  public $orderAscadd = 'desc';
  public $prod = [];
  public $item;
  public $itemselected = null;
  public $productid;
  public $allow = false;
  public $update = false;



  public function render()
  {
    return view('livewire.related-productson-pricelist', [
      'relatedprods' => $this->relatedprods,
      'addprods' => $this->addprods
    ]);
  }
  public function mount($priceId)
  {
    $this->priceId = $priceId;
    $this->selectedColumns = $this->columns;
    $this->item = PriceList::find($priceId);
  }

  //function for realted
  public function showColumn($column)
  {
    if ($column === 'Product name') {
      return true;
    }
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->relatedprods->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedprodsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getRelatedprodsProperty()
  {
    return $this->relatedprodsQuery->paginate($this->perPage);
  }
  public function getRelatedprodsQueryProperty()
  {
    return PricelistEntries::where('pricelist_id', $this->priceId)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('product');
  }
  public function confirmRemoval($id)
  {
    $this->idtodel = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function deleteSingleRecord()
  {
    $id = $this->idtodel;
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
  public function editprod($id, $idprod)
  {
    $this->update = true;
    $this->addrelatedproducts = true;
    $this->itemselected = Product::find($idprod)->name;
    $this->productid = $id;
  }
  public function confirmprod()
  {
    $val = $this->prod;
    if (array_key_exists('value', $val)) {
      $new = PricelistEntries::find($this->productid);
      $new->value = $val['value'];
      $new->save();
      $this->addrelatedproducts = false;
      $this->allow = false;
      $this->productid = null;
      $this->prod = [];
      $this->itemselected = null;
      $this->search = '';
      $this->update = false;
      session()->flash('message', 'Related product edited succesfuly');
    } else {
      session()->flash('message', 'Please provide a value!');
    }
  }
  // add specs function
  public function addrelated()
  {
    $this->showrelatedprods = true;
    $this->addrelatedproducts = true;
  }
  public function select($id)
  {
    $this->itemselected = Product::find($id)->name;
    $this->productid = $id;
    $this->allow = false;
  }
  public function allowselect()
  {
    $this->allow = true;
  }
  public function closemodal()
  {
    $this->addrelatedproducts = false;
    $this->allow = false;
    $this->itemselected = null;
    $this->update = false;
  }
  public function saveprod()
  {
    $val = $this->prod;
    if (array_key_exists('value', $val)) {
      $new = new PricelistEntries();
      $new->product_id = $this->productid;
      $new->pricelist_id = $this->priceId;
      $new->value = $val['value'];
      $new->save();
      $this->addrelatedproducts = false;
      $this->allow = false;
      $this->productid = null;
      $this->prod = [];
      $this->itemselected = null;
      $this->search = '';
      session()->flash('message', 'Product related succesfuly succesfuly');
    } else {
      session()->flash('message', 'Please provide a value!');
    }
  }
  public function getAddprodsProperty()
  {
    return $this->addprodsQuery->get();
  }
  public function getAddprodsQueryProperty()
  {
    return Product::search($this->searchadd)->orderBy($this->orderByadd, $this->orderAscadd);
  }
}
