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
  public $columns = ['Id', 'Currency', 'Value', 'TVA', 'Created At'];
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
  public $isselected;

  public function render()
  {
    $relatedprices = $this->relatedpricesQuery
      ->where(function ($query) {
        $query->whereHas('pricelist', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
        });
      })->get();
    return view('livewire.related-pricelist', [
      'relatedprices' => $relatedprices,
      'addprices' => $this->addprices,
    ]);
  }
  public function mount(Product $product)
  {
    $this->selectedColumns = $this->columns;
    $this->item = $product;
    $this->priceAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'price' => ['idrel' => null, 'value' => null, 'tva' => 19],
    ];
  }
  public function load()
  {
    $this->perPage += 10;
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
    return $this->relatedpricesQuery;
  }
  public function getRelatedpricesQueryProperty()
  {
    return PricelistEntries::where('product_id', $this->item->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('pricelist.currency');
  }
  public function confirmRemoval($id)
  {
    $this->priceidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal-price');
  }
  public function deleteSingleRecord()
  {
    $id = $this->priceidbeingremoved;
    $item = PricelistEntries::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
    $this->all = false;
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
      $index . '.tva' => $val->tva_percent,
    ];
  }
  public function canceledit()
  {
    $this->editedrow = null;
    $this->allow = false;
    $this->itemselected = null;
    $this->pricelist = [];
  }
  public function confirmitem($index, $id)
  {
    $new = PricelistEntries::find($id);
    if ($this->isselected != null) {
      if ($this->isselected) {
        $new->pricelist_id = $this->priceid;
        $new->save();
      } else {
        session()->flash('notification', [
          'message' => 'Please provide a value!',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
      }
    }

    $val = $this->pricelist[$index] ?? NULL;
    if (!is_null($val)) {
      if (array_key_exists('value', $val)) {
        // Replace commas with dots for consistent decimal representation
        $newValue = str_replace(',', '.', $val["value"]);
        // Convert the string to a float
        $floatValue = floatval($newValue);
        // Format the float to have two decimal places
        $formattedValue = number_format($floatValue, 2, '.', '');

        $new->value = $formattedValue;
      }
      $new->save();
      $this->allow = false;
      $this->priceid = null;
      $this->pricelist = [];
      $this->itemselected = null;
      $this->editedrow = null;
      $this->search = '';
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      $this->allow = false;
      $this->priceid = null;
      $this->pricelist = [];
      $this->itemselected = null;
      $this->editedrow = null;
      $this->search = '';
      session()->flash('notification', [
        'message' => 'Nothing change!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
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
      $this->priceAndValues[$index]['price']['tva'] = $test->tva_percent;
      $this->priceAndValues[$index]['allow'] = false;
    }
  }
  public function confirmpricemultiple()
  {
    if (empty($this->priceAndValues)) {
      session()->flash('notification', [
        'message' => 'No specifications to update.',
        'type' => 'warning',
        'title' => 'No Data'
      ]);
      return;
    }
    foreach ($this->priceAndValues as  $priceAndValue) {
      if (!empty($priceAndValue['price']['value'])) {
        $item = PricelistEntries::find($priceAndValue['price']['id']);
        if ($item) {
          $item->tva_percent = $priceAndValue['price']['tva'];
          $item->pricelist_id = $priceAndValue['price']['idrel'];
          $item->value = $priceAndValue['price']['value'];
          $item->save();
        }
      } else {
        session()->flash('notification', [
          'message' => 'Please provide a value!',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
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
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->mount($this->item);
  }
  public function allow()
  {
    $this->allow = true;
    $this->isselected = false;
    $this->searchadd = $this->itemselected->name;
  }
  public function select($id)
  {
    $this->itemselected = PriceList::find($id);
    $this->priceid = $id;
    $this->allow = false;
    $this->isselected = true;
  }
  public function dennyselect()
  {

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
    foreach ($this->priceAndValues as &$item) {
      $item['allow'] = false;
    }
    $this->priceAndValues[$index]['allow'] = true;
    $this->searchadd = $this->priceAndValues[$index]['itemselected'];
  }
  public function denny($index)
  {
    $this->priceAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function selectitem($index, $id, $name)
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
      'price' => ['name' => null, 'value' => null, 'tva' => 19],
    ];
  }
  public function clear($index)
  {
    unset($this->priceAndValues[$index]);

    $this->priceAndValues = array_values($this->priceAndValues);

    $this->row--;
    if ($this->row < 1) {
      $this->addrelatedprice = false;
      $this->priceAndValues =
        [
          [
            'allow' => false,
            'itemselected' => null,
            'price' => ['name' => null, 'value' => null, 'tva' => 19],
          ]
        ];
      $this->row = 1;
    }
  }
  public function saveitems()
  {
    foreach ($this->priceAndValues as  $priceAndValue) {
      if (isset($priceAndValue['price']['value'])) {
        $new = new PricelistEntries();
        $new->product_id = $this->item->id;
        $new->pricelist_id = $priceAndValue['price']['idrel'];
        $new->value = $priceAndValue['price']['value'];
        $new->tva_percent = $priceAndValue['price']['tva'];
        $new->save();
      } else {
        session()->flash('notification', [
          'message' => 'Please provide a value!',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
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
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->mount($this->item);
  }
  public function getAddpricesProperty()
  {
    $ids = $this->relatedprices->pluck('pricelist_id')->toArray();
    $unrelated = PriceList::whereNotIn('id', $ids);
    if (!empty($this->searchadd)) {
      $unrelated->where('name', 'like', '%' . $this->searchadd . '%');
    }
    $unrelated->with('currency')->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
    return $unrelated->get();
  }
}
