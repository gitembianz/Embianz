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
  public $editmultiple = false;
  public $itemstoedit;
  public $priceAndValues = [];
  public $row = 1;
  public $editedrow;
  public $pricelist;

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
    $this->priceAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'price' => ['idrel' => null, 'value' => null],
    ];
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
  public function edititem($id, $iditem, $index)
  {
    $this->itemselected = PriceList::find($iditem);
    $val = PricelistEntries::find($id);
    $this->priceid = $iditem;
    $this->editedrow = $index;
    $this->pricelist = [
      $index . '.name' => $this->itemselected,
      $index . '.value' => $val->value,
    ];
  }
  public function canceledit()
  {
    $this->editedrow = null;
    $this->allow = false;
    $this->itemselected = null;
    $this->pricelist = [];
  }
  public function confirmprice($index, $id)
  {
    $new = PricelistEntries::find($id);
    $new->pricelist_id = $this->priceid;
    $val = $this->pricelist;
    if (isset($val["$index"]['value'])) {

      $new->value = $val["$index"]['value'];
    }

    $new->save();
    $this->allow = false;
    $this->priceid = null;
    $this->pricelist = [];
    $this->itemselected = null;
    $this->editedrow = null;
    $this->search = '';
    session()->flash('message', 'Records edited succesfuly');
  }

  //function for add new pricelist
  public function addrelated()
  {
    $this->showrelatedprice = true;
    $this->addrelatedprice = true;
  }
  public function editSelected()
  {
    $this->itemstoedit = $this->checked;
    $this->editmultiple = true;
    foreach ($this->itemstoedit  as $index => $item) {
      $test = PricelistEntries::find($item);
      $this->priceAndValues[$index]['itemselected'] = $test->pricelist->name;
      $this->priceAndValues[$index]['price']['id'] = $test->id;
      $this->priceAndValues[$index]['price']['idrel'] = $test->pricelist->id;
      $this->priceAndValues[$index]['price']['value'] = $test->value;
      $this->priceAndValues[$index]['allow'] = false;
    }
  }
  public function confirmpricemultiple()
  {

    foreach ($this->priceAndValues as  $priceAndValue) {
      if (isset($priceAndValue['price']['value'])) {
        $item = PricelistEntries::find($priceAndValue['price']['id']);
        $item->pricelist_id = $priceAndValue['price']['idrel'];
        $item->value = $priceAndValue['price']['value'];
        $item->save();
      } else {
        session()->flash('message', 'Please provide a value!');
        return;
      }
    }

    $this->priceAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'price' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->checked = [];
    $this->all = false;
    $this->editmultiple = false;
    session()->flash('message', 'Pricelist edited successfully.');
  }
  public function allow()
  {
    $this->allow = true;
    $this->searchadd = $this->itemselected->name;
  }

  public function select($id)
  {
    $this->itemselected = PriceList::find($id);
    $this->priceid = $id;
    $this->allow = false;
  }
  public function closemodal()
  {
    $this->priceAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'spec' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->checked = [];
    $this->all = false;
    $this->editmultiple = false;
    $this->addrelatedprice = false;
  }
  public function allowselect($index)
  {
    $this->priceAndValues[$index]['allow'] = true;
    $this->searchadd = $this->priceAndValues[$index]['itemselected'];
  }
  public function selectSpec($index, $id, $name)
  {
    $this->priceAndValues[$index]['itemselected'] = $name;
    $this->priceAndValues[$index]['price']['idrel'] = $id;
    $this->priceAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function plus()
  {
    $this->row++;
    $this->priceAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'price' => ['name' => null, 'value' => null],
    ];
  }
  //clear one row in modal
  public function clear($index)
  {
    // Remove the row from the array
    unset($this->priceAndValues[$index]);

    // Reset the keys of the array
    $this->priceAndValues = array_values($this->priceAndValues);

    // Decrement the total row count
    $this->row--;
  }

  public function saveitems()
  {
    foreach ($this->priceAndValues as $index =>  $priceAndValue) {
      if (isset($priceAndValue['price']['value'])) {
        $new = new PricelistEntries();
        $new->product_id = $this->productId;
        $new->pricelist_id = $priceAndValue['price']['idrel'];
        $new->value = $priceAndValue['price']['value'];
        $new->save();
      } else {
        session()->flash('message', 'Please provide a value!');
        return;
      }
    }

    $this->priceAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'price' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->addrelatedprice = false;
    session()->flash('message', 'Pricelists related successfully.');
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
