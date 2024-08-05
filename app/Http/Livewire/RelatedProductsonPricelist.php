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
  public $columns = ['Id', 'Currency', 'Value', 'Discount', 'Value without VAT', 'Value without Discount', 'VAT'];

  public $selectedColumns = [];
  public $idbeingremoved = null;
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
  public $row = 1;
  public $editedrow;
  public $product;
  public $editmultiple = false;
  public $itemstoedit;
  public $rind = null;
  public $rind2 = null;
  public $rind3 = null;
  public $single = false;
  public $multiple = false;

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
  public function expandRow3($index)
  {
    if ($this->rind3  === null) {
      $this->rind3 = $index;
    } elseif ($this->rind3 != $index) {
      $this->rind3 = $index;
    } else {
      $this->rind3 = null;
    }
  }

  public function render()
  {
    $relatedprods = $this->relatedprods
      ->where(function ($query) {
        $query->whereHas('product', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
        });
      })->get();
    return view('livewire.related-productson-pricelist', [
      'relatedprods' => $relatedprods,
      'addprods' => $this->addprods
    ]);
  }
  public function mount($priceId)
  {
    $this->priceId = $priceId;
    $this->selectedColumns = $this->columns;
    $this->item = PriceList::find($priceId);
    $this->prod[] = [
      'allow' => false,
      'itemselected' => null,
      'product' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0],
    ];
  }
  public function denny()
  {
    $this->allow = false;
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
    return $this->relatedprodsQuery;
  }
  public function getRelatedprodsQueryProperty()
  {
    return PricelistEntries::where('pricelist_id', $this->priceId)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('product');
  }
  public function deleteSingleRecord()
  {
    $id = $this->idbeingremoved;
    $item = PricelistEntries::findOrFail($id);
    $item->delete();
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
    $items = PricelistEntries::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemtodel = PricelistEntries::find($id);
      $itemtodel->delete();
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

  public function edititem($id, $iditem, $index)
  {
    $this->itemselected = Product::find($iditem)->name;
    $val = PricelistEntries::find($id);
    $this->productid = $iditem;
    $this->editedrow = $index;
    $this->product = [
      $index . '.name' => $this->itemselected,
      $index . '.value' => $val->value_no_vat,
      $index . '.vat' => $val->vat,
      $index . '.discount' => $val->discount,
    ];
  }
  public function allow()
  {
    $this->allow = true;
    $this->searchadd = $this->itemselected;
  }
  public function confirmitem($index, $id)
  {
    $new = PricelistEntries::find($id);
    $new->product_id = $this->productid;
    $val = $this->product[$index] ?? NULL;
    if (array_key_exists('vat', $val)) {
      if ($val["vat"] === "") {
        $val["vat"] = 0;
      }

      if ($val["vat"] < 0) {
        session()->flash('notification', [
          'message' => 'Please provide a value biger than 0!',
          'type' => 'warning',
          'title' => 'VAT value'
        ]);
        $this->product = [
          $index . '.name' => $this->itemselected,
          $index . '.value' => $new->value_no_vat,
          $index . '.vat' => $val["vat"],
          $index . '.discount' => $new->discount,
        ];
        return;
      }

      $new->vat = $val["vat"];
      $new->save();
    }
    if (array_key_exists('discount', $val)) {
      if ($val["discount"] === "") {
        $val["discount"] = 0;
      }
      if ($val["discount"] < 0 || $val["discount"] >= 100) {
        session()->flash('notification', [
          'message' => 'Please provide a value biger than 0 and smaller that 100!',
          'type' => 'warning',
          'title' => 'Discount value'
        ]);
        $this->product = [
          $index . '.name' => $this->itemselected,
          $index . '.value' => $new->value_no_vat,
          $index . '.vat' => $new->vat,
          $index . '.discount' => $val["discount"],
        ];
        return;
      } else {
        $new->discount = $val["discount"];
        $new->save();
      }
    }
    if (array_key_exists('value', $val)) {
      $newValue = str_replace(',', '.', $val["value"]);
      $floatValue = floatval($newValue);
      $formattedValue = number_format($floatValue, 2, '.', '');

      $new->value_no_vat = $formattedValue;
      $new->save();
    }
    $new->value_no_discount = $new->value_no_vat + (0.01 * $new->vat * $new->value_no_vat);
    $new->value = $new->value_no_discount - (0.01 * $new->value_no_discount * $new->discount);
    $new->save();
    $this->allow = false;
    $this->productid = null;
    $this->product = [];
    $this->itemselected = null;
    $this->editedrow = null;
    $this->search = '';

    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function canceledit()
  {
    $this->editedrow = null;
    $this->allow = false;
    $this->itemselected = null;
    $this->product = [];
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
  public function closemodal()
  {
    $this->prod = [
      [
        'allow' => false,
        'itemselected' => null,
        'product' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0],
      ]
    ];
    $this->row = 1;
    $this->checked = [];
    $this->editmultiple = false;
    $this->addrelatedproducts = false;
  }
  public function load()
  {
    $this->perPage += 10;
  }
  public function saveitems()
  {
    foreach ($this->prod as  $pro) {
      if (isset($pro['product']['value']) && isset($pro['product']['idrel'])) {
        $new = new PricelistEntries();
        $new->product_id = $pro['product']['idrel'];
        $new->pricelist_id =  $this->priceId;
        $new->vat = $pro['product']['vat'];
        $new->discount = $pro['product']['discount'];
        $new->value_no_vat = $pro['product']['value'];
        $new->value_no_discount = $pro['product']['value'] + (0.01 * $pro['product']['vat'] * $pro['product']['value']);
        $new->value = $pro['product']['value'] - (0.01 * $pro['product']['discount'] * $new->value_no_discount) + (0.01 * $pro['product']['vat'] * $pro['product']['value']);
        $new->save();
      } else {
        session()->flash('notification', [
          'message' => 'Please provide all corect values for Value without VAT, VAT(bigger than 0) and Discount(bigger than 0 and smaller than 100)!',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
        return;
      }
    }

    $this->prod = [
      [
        'allow' => false,
        'itemselected' => null,
        'product' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0],
      ]
    ];
    $this->row = 1;
    $this->addrelatedproducts = false;
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function dennyselect($index)
  {
    $this->prod[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function plus()
  {
    $this->row++;
    $this->prod[] = [
      'allow' => false,
      'itemselected' => null,
      'product' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0],
    ];
  }
  public function clear($index)
  {
    unset($this->prod[$index]);
    $this->prod = array_values($this->prod);

    // Decrement the total row count
    $this->row--;
    if ($this->row < 1) {
      $this->addrelatedproducts = false;
      $this->prod = [
        [
          'allow' => false,
          'itemselected' => null,
          'product' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0],
        ]
      ];
      $this->row = 1;
    }
  }
  public function allowselect($index)
  {
    foreach ($this->prod as &$item) {
      $item['allow'] = false;
    }
    $this->prod[$index]['allow'] = true;
    $this->searchadd = $this->prod[$index]['itemselected'];
  }
  public function selectitem($index, $id, $name)
  {
    $this->prod[$index]['itemselected'] = $name;
    $this->prod[$index]['product']['idrel'] = $id;
    $this->prod[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function editSelected()
  {
    $this->itemstoedit = $this->checked;
    $this->editmultiple = true;
    foreach ($this->itemstoedit  as $index => $item) {
      $test = PricelistEntries::find($item);
      $this->prod[$index]['itemselected'] = $test->product->name;
      $this->prod[$index]['product']['id'] = $test->id;
      $this->prod[$index]['product']['idrel'] = $test->product->id;
      $this->prod[$index]['product']['value'] = $test->value_no_vat;
      $this->prod[$index]['product']['discount'] = $test->discount;
      $this->prod[$index]['product']['vat'] = $test->vat;
      $this->prod[$index]['allow'] = false;
    }
  }
  public function confirmmultiple()
  {

    foreach ($this->prod as $index => $pro) {
      if (isset($pro['product']['value'])) {
        $item = PricelistEntries::find($pro['product']['id']);
        $item->product_id = $pro['product']['idrel'];
        $item->value_no_vat = $pro['product']['value'];

        if ($pro['product']['vat'] < 0) {
          session()->flash('notification', [
            'message' => 'Please provide a value bigger than 0!',
            'type' => 'warning',
            'title' => 'VAT value'
          ]);
          return;
        }
        $item->vat = $pro['product']['vat'];

        if ($pro['product']['discount'] < 0 || $pro['product']['discount'] >= 100) {
          session()->flash('notification', [
            'message' => 'Please provide a value bigger than 0 and smaller than 100!',
            'type' => 'warning',
            'title' => 'Discount value'
          ]);
          return;
        }
        $item->discount = $pro['product']['discount'];
        $item->value_no_discount = $item->value_no_vat + (0.01 * $item->vat * $item->value_no_vat);
        $item->value = $item->value_no_discount - (0.01 * $item->value_no_discount * $item->discount);

        $item->save();
        unset($this->prod[$index]);
        $this->prod = array_values($this->prod);
      } else {
        session()->flash('notification', [
          'message' => 'Please provide a value!',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
        return;
      }
    }

    $this->prod = [
      [
        'allow' => false,
        'itemselected' => null,
        'product' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0],
      ]
    ];
    $this->row = 1;
    $this->checked = [];

    $this->editmultiple = false;
    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function getAddprodsProperty()
  {
    $ids = $this->relatedprods->pluck('product_id')->toArray();
    $unrelated = Product::whereNotIn('id', $ids);
    if (!empty($this->searchadd)) {
      $unrelated->where('name', 'like', '%' . $this->searchadd . '%');
    }
    $unrelated->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
    return $unrelated->get();
  }
}