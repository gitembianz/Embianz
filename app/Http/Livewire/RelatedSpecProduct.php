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
  public $itemselected;
  //Add specs declaration
  public $searchadd = '';
  public $orderByadd = 'updated_at';
  public $orderAscadd = 'desc';
  public $item;
  public $specid;
  public $allow = false;
  public $update = false;
  public $specsAndValues = [];
  public $row = 1;
  public $editedrow;
  public $specification;
  public $editmultiple = false;
  public $itemstoedit;


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
    $this->specsAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'spec' => ['name' => null, 'value' => null],
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
    return $this->relatedspecsQuery->paginate($this->perPage);
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
  public function editspec($id, $idspec, $index)
  {

    $this->itemselected = Specs::find($idspec);
    $val = Product_Spec::find($id);
    $this->specid = $idspec;
    $this->editedrow = $index;
    $this->specification = [
      $index . '.name' => $this->itemselected,
      $index . '.value' => $val->value,
    ];
  }
  public function canceledit()
  {
    $this->editedrow = null;
    $this->allow = false;
    $this->itemselected = null;
    $this->specification = [];
  }
  public function confirmspecs()
  {
    dd($this->specsAndValues);
    foreach ($this->specsAndValues as $index =>  $specAndValue) {
      $val = $specAndValue;
      if (array_key_exists('value', $val)) {
        $newspec = new Product_Spec();
        $newspec->product_id = $this->productId;
        $newspec->spec_id = $specAndValue['spec']['idrel'];
        $newspec->value = $specAndValue['spec']['value'];
        $newspec->save();
      } else {
        session()->flash('message', 'Please provide a value!');
        return;
      }
    }

    $this->specsAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'spec' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->addrelatedspecs = false;
    session()->flash('message', 'Specs related successfully.');
  }
  public function editSelected()
  {
    $this->itemstoedit = $this->checked;
    $this->editmultiple = true;
    foreach ($this->itemstoedit  as $index => $item) {
      $test = Product_Spec::find($item);
      $this->specsAndValues[$index]['itemselected'] = $test->spec->name;
      $this->specsAndValues[$index]['spec']['id'] = $test->id;
      $this->specsAndValues[$index]['spec']['value'] = $test->value;
      $this->specsAndValues[$index]['allow'] = false;
    }
  }
  // add specs function
  public function addrelated()
  {
    $this->showrelatedspecs = true;
    $this->addrelatedspecs = true;
  }
  public function select($id)
  {
    $this->itemselected = Specs::find($id);
    $this->specid = $id;
    $this->allow = false;
  }
  public function selectSpec($index, $id, $name)
  {
    $this->specsAndValues[$index]['itemselected'] = $name;
    $this->specsAndValues[$index]['spec']['idrel'] = $id;
    $this->specsAndValues[$index]['allow'] = false;
  }
  public function plus()
  {
    $this->row++;
    $this->specsAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'spec' => ['name' => null, 'value' => null],
    ];
  }
  public function allowselect($index)
  {
    $this->specsAndValues[$index]['allow'] = true;
  }
  public function allow()
  {
    $this->allow = true;
    $this->searchadd = $this->itemselected->name;
  }
  public function closemodal()
  {
    $this->specsAndValues = [
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
    $this->addrelatedspecs = false;
  }
  public function clear($index)
  {
    // Remove the row from the array
    unset($this->specsAndValues[$index]);

    // Reset the keys of the array
    $this->specsAndValues = array_values($this->specsAndValues);

    // Decrement the total row count
    $this->row--;
  }

  public function savespecs()
  {
    foreach ($this->specsAndValues as $index =>  $specAndValue) {
      $val = $specAndValue['spec'];
      if (array_key_exists('value', $val)) {
        $newspec = new Product_Spec();
        $newspec->product_id = $this->productId;
        $newspec->spec_id = $specAndValue['spec']['idrel'];
        $newspec->value = $specAndValue['spec']['value'];
        $newspec->save();
      } else {
        session()->flash('message', 'Please provide a value!');
        return;
      }
    }

    $this->specsAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'spec' => ['name' => null, 'value' => null],
      ]
    ];
    $this->row = 1;
    $this->addrelatedspecs = false;
    session()->flash('message', 'Specs related successfully.');
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
