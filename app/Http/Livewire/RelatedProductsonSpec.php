<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Product_Spec;
use App\Models\Specs;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedProductsonSpec extends Component
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
  public $specId;
  public $col = false;
  public $all = false;
  public $columns = ['Id', 'Unit', 'Value', 'Created At'];
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
  public $row = 1;
  public $editedrow;
  public $product;
  public $editmultiple = false;
  public $itemstoedit;


  public function render()
  {
    $relatedprods = $this->relatedprods
      ->where(function ($query) {
        $query->whereHas('product', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
        });
      })->get();
    if ($this->addrelatedproducts === true || $this->editmultiple  || $this->allow === true) {
      return view('livewire.related-productson-spec', [
        'relatedprods' => $relatedprods,
        'addprods' => $this->addprods
      ]);
    } else {
      return view('livewire.related-productson-spec', [
        'relatedprods' => $relatedprods
      ]);
    }
  }
  public function mount($specId)
  {
    $this->specId = $specId;
    $this->selectedColumns = $this->columns;
    $this->item = Specs::find($specId);
    $this->prod[] = [
      'allow' => false,
      'itemselected' => null,
      'product' => ['idrel' => null, 'value' => null],
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
  public function denny()
  {
    $this->allow = false;
  }
  public function getRelatedprodsProperty()
  {
    return $this->relatedprodsQuery;
  }
  public function getRelatedprodsQueryProperty()
  {
    return Product_Spec::where('spec_id', $this->specId)
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
    $item = Product_Spec::findOrFail($id);
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
    $items = Product_Spec::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemtodel = Product_Spec::find($id);
      $itemtodel->delete();
    }
    $this->checked = [];
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
  public function editprod($id, $idprod, $index)
  {
    $this->itemselected = Product::find($idprod)->name;
    $val = Product_Spec::find($id);
    $this->productid = $idprod;
    $this->editedrow = $index;
    $this->allow = false;
    $this->product = [
      $index . '.name' => $this->itemselected,
      $index . '.value' => $val->value,
    ];
  }
  public function confirmprod($index, $id)
  {
    $prod = Product_Spec::find($id);
    $prod->product_id = $this->productid;
    $val = $this->product;
    if (isset($val["$index"]['value'])) {
      if (!empty($val["$index"]['value'])) {
        $prod->value = $val["$index"]['value'];
        $prod->save();
        $this->allow = false;
        $this->productid = null;
        $this->product = [];
        $this->itemselected = null;
        $this->editedrow = null;
        $this->search = '';
      } else {
        session()->flash('notification', [
          'message' => 'Please provide a value!',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
        return;
      }
    }
    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function editSelected()
  {
    $this->itemstoedit = $this->checked;
    $this->editmultiple = true;
    foreach ($this->itemstoedit  as $index => $item) {
      $test = Product_Spec::find($item);
      $this->prod[$index]['itemselected'] = $test->product->name;
      $this->prod[$index]['product']['id'] = $test->id;
      $this->prod[$index]['product']['idrel'] = $test->product->id;
      $this->prod[$index]['product']['value'] = $test->value;
      $this->prod[$index]['allow'] = false;
    }
  }
  public function confirmmultiple()
  {
    if (empty($this->prod)) {
      session()->flash('notification', [
        'message' => 'No specifications to update.',
        'type' => 'warning',
        'title' => 'No Data'
      ]);
      return;
    }

    foreach ($this->prod as $pro) {
      if (isset($pro['product']['value'])) {
        $prodd = Product_Spec::find($pro['product']['id']);
        if ($prodd) {
          $prodd->product_id = $pro['product']['idrel'];
          $prodd->value = $pro['product']['value'];
          $prodd->save();
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

    $this->prod = [
      [
        'allow' => false,
        'itemselected' => null,
        'product' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->checked = [];
    $this->all = false;
    $this->editmultiple = false;
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Records edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  // add specs function
  public function addrelated()
  {
    $this->showrelatedprods = true;
    $this->addrelatedproducts = true;
  }
  public function canceledit()
  {
    $this->editedrow = null;
    $this->allow = false;
    $this->itemselected = null;
    $this->product = [];
  }
  public function allow()
  {
    $this->allow = true;
    $this->searchadd = $this->itemselected;
  }
  public function select($id)
  {
    $this->itemselected = Product::find($id)->name;
    $this->productid = $id;
    $this->allow = false;
  }
  public function plus()
  {
    $this->row++;
    $this->prod[] = [
      'allow' => false,
      'itemselected' => null,
      'product' => ['name' => null, 'value' => null],
    ];
  }
  public function selectitem($index, $id, $name)
  {
    $this->prod[$index]['itemselected'] = $name;
    $this->prod[$index]['product']['idrel'] = $id;
    $this->prod[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function load()
  {
    $this->perPage += 10;
  }
  public function allowselect($index)
  {
    foreach ($this->prod as &$item) {
      $item['allow'] = false;
    }
    $this->prod[$index]['allow'] = true;
    $this->searchadd = $this->prod[$index]['itemselected'];
  }
  public function dennyselect($index)
  {
    $this->prod[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function closemodal()
  {
    $this->prod = [
      [
        'allow' => false,
        'itemselected' => null,
        'product' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->checked = [];
    $this->all = false;
    $this->editmultiple = false;
    $this->addrelatedproducts = false;
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
          'product' => ['name' => null, 'value' => null],
        ]
      ];
      $this->row = 1;
    }
  }
  public function saveprod()
  {
    $empty = false;
    foreach ($this->prod as  $pro) {
      $val = $pro['product'];
      if (array_key_exists('value', $val) && $pro['product']['value'] == null) {
        $empty = true;
      }
    }
    if ($empty) {
      session()->flash('notification', [
        'message' => 'Please provide a value!',
        'type' => 'warning',
        'title' => 'Missing Values'
      ]);
      return;
    } else {
      foreach ($this->prod as $pro) {
        $news = new Product_Spec();
        $news->product_id = $pro['product']['idrel'];
        $news->spec_id = $this->specId;
        $news->value = $pro['product']['value'];
        $news->save();
      }
    }

    $this->prod = [
      [
        'allow' => false,
        'itemselected' => null,
        'product' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->addrelatedproducts = false;
    session()->flash('notification', [
      'message' => 'Records related successfully!',
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
