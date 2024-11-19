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
  public $columns = ['Id', 'Name', 'Currency', 'Value', 'Discount', 'Value without VAT', 'Value without Discount', 'VAT', 'PRICE'];
  public $selectedColumns = [];
  public $idbeingremoved = null;
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
  public $editedrow;
  public $pricelist;
  public $isselected;

  public $single = false;
  public $multiple = false;
  public $row = null;
  public $row2 = null;
  public $row3 = null;
  public $row4 = 1;

  public function expandRow($index)
  {
    if ($this->row  === null) {
      $this->row = $index;
    } elseif ($this->row != $index) {
      $this->row = $index;
    } else {
      $this->row = null;
    }
  }
  public function expandRow2($index)
  {
    if ($this->row2  === null) {
      $this->row2 = $index;
    } elseif ($this->row2 != $index) {
      $this->row2 = $index;
    } else {
      $this->row2 = null;
    }
  }
  public function expandRow3($index)
  {
    if ($this->row3  === null) {
      $this->row3 = $index;
    } elseif ($this->row3 != $index) {
      $this->row3 = $index;
    } else {
      $this->row3 = null;
    }
  }

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
      'price' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0, 'price' => null],
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
      $this->checked = $this->relatedprices->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedpricesQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
    $this->itemselected = PriceList::find($iditem);
    $val = PricelistEntries::find($id);
    $this->priceid = $iditem;
    $this->editedrow = $index;
    $this->pricelist = [
      $index . '.value' => $val->value_no_vat,
      $index . '.vat' => $val->vat,
      $index . '.discount' => $val->discount,
      $index . '.price' => $val->price,

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
    $val = $this->pricelist[$index] ?? NULL;
    if (!is_null($val)) {
      if (array_key_exists('price', $val)) {
        $new->price = $val["price"];
      }

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
          $this->pricelist = [
            $index . '.value' => $new->value_no_vat,
            $index . '.vat' => $val["vat"],
            $index . '.discount' => $new->discount,
          ];
          return;
        }

        $new->vat = $val["vat"];
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
          $this->pricelist = [
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
      }
      $new->value_no_discount = $new->value_no_vat + (0.01 * $new->vat * $new->value_no_vat);
      $new->value = $new->value_no_discount - (0.01 * $new->value_no_discount * $new->discount);
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

  // fct de edit multiple
  public function editSelected()
  {
    $this->itemstoedit = $this->checked;
    $this->editmultiple = true;
    foreach ($this->itemstoedit  as $index => $item) {
      $test = PricelistEntries::find($item);
      $this->priceAndValues[$index]['itemselected'] = $test->pricelist->name;
      $this->priceAndValues[$index]['price']['id'] = $test->id;
      $this->priceAndValues[$index]['price']['value'] = $test->value_no_vat;
      $this->priceAndValues[$index]['price']['discount'] = $test->discount;
      $this->priceAndValues[$index]['price']['vat'] = $test->vat;
      $this->priceAndValues[$index]['price']['price'] = $test->price;
    }
  }
  public function confirmpricemultiple()
  {
    if (empty($this->priceAndValues)) {
      session()->flash('notification', [
        'message' => 'No pricelist to update.',
        'type' => 'warning',
        'title' => 'No Data'
      ]);
      return;
    }


    foreach ($this->priceAndValues as $index => $priceAndValue) {
      if (!empty($priceAndValue['price']['value'])) {

        $item = PricelistEntries::find($priceAndValue['price']['id']);
        if ($item) {
          $item->value_no_vat = $priceAndValue['price']['value'];
          $item->price = $priceAndValue['price']['price'];

          if ($priceAndValue['price']['vat'] < 0) {
            session()->flash('notification', [
              'message' => 'Please provide a value bigger than 0!',
              'type' => 'warning',
              'title' => 'VAT value'
            ]);
            return;
          }
          $item->vat = $priceAndValue['price']['vat'];
          if ($priceAndValue['price']['discount'] < 0 || $priceAndValue['price']['discount'] >= 100) {
            session()->flash('notification', [
              'message' => 'Please provide a value bigger than 0 and smaller than 100!',
              'type' => 'warning',
              'title' => 'Discount value'
            ]);
            return;
          }
          $item->discount = $priceAndValue['price']['discount'];
          $item->value_no_discount = $item->value_no_vat + (0.01 * $item->vat * $item->value_no_vat);
          $item->value = $item->value_no_discount - (0.01 * $item->value_no_discount * $item->discount);

          $item->save();
          unset($this->priceAndValues[$index]);
          $this->priceAndValues = array_values($this->priceAndValues);
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
        'price' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0, 'price' => null],
      ]
    ];
    $this->row = 1;
    $this->checked = [];

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
        'price' => ['idrel' => null, 'value' => null, 'vat' => 19, 'discount' => 0, 'price' => null],
      ]
    ];
    $this->row = 1;
    $this->checked = [];

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
    $this->row4++;
    $this->priceAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'price' => ['name' => null, 'value' => null, 'vat' => 19, 'discount' => 0, 'price' => null],
    ];
  }
  public function clear($index)
  {
    unset($this->priceAndValues[$index]);

    $this->priceAndValues = array_values($this->priceAndValues);

    $this->row4--;
    if ($this->row4 < 1) {
      $this->addrelatedprice = false;
      $this->priceAndValues =
        [
          [
            'allow' => false,
            'itemselected' => null,
            'price' => ['name' => null, 'value' => null, 'vat' => 19, 'discount' => 0, 'price' => null],
          ]
        ];
      $this->row4 = 1;
    }
  }
  public function saveitems()
  {
    foreach ($this->priceAndValues as  $priceAndValue) {
      if (isset($priceAndValue['price']['value']) && isset($priceAndValue['price']['idrel'])) {
        $new = new PricelistEntries();
        $new->product_id = $this->item->id;
        $new->pricelist_id = $priceAndValue['price']['idrel'];
        $new->vat = $priceAndValue['price']['vat'];
        $new->price = $priceAndValue['price']['price'];

        $new->discount = $priceAndValue['price']['discount'];
        $new->value_no_vat =  str_replace(',', '.', $priceAndValue['price']['value']);
        $new->value_no_discount = str_replace(',', '.', $priceAndValue['price']['value']) + (0.01 * $priceAndValue['price']['vat'] * str_replace(',', '.', $priceAndValue['price']['value']));
        $new->value = str_replace(',', '.', $priceAndValue['price']['value']) - (0.01 * $priceAndValue['price']['discount'] * $new->value_no_discount) + (0.01 * $priceAndValue['price']['vat'] * str_replace(',', '.', $priceAndValue['price']['value']));
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

    $this->priceAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'price' => ['name' => null, 'value' => null, 'vat' => 19, 'discount' => 0, 'price' => null],
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
