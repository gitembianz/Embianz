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
  public $item;
  public $specid;
  public $allow = false;
  public $update = false;
  public $specsAndValues = [];
  public $row = 1;


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
  // public function editspec($id, $idspec)
  // {
  //   $this->update = true;
  //   $this->addrelatedspecs = true;
  //   $this->itemselected = Specs::find($idspec)->name;
  //   $this->specid = $id;
  // }
  // public function confirmspecs()
  // {
  //   $val = $this->spec;
  //   if (array_key_exists('value', $val)) {
  //     $newspec = Product_Spec::find($this->specid);
  //     $newspec->value = $val['value'];
  //     $newspec->save();
  //     $this->addrelatedspecs = false;
  //     $this->allow = false;
  //     $this->specid = null;
  //     $this->spec = [];
  //     $this->itemselected = null;
  //     $this->search = '';
  //     $this->update = false;
  //     session()->flash('message', 'Spec related succesfuly succesfuly');
  //   } else {
  //     session()->flash('message', 'Please provide a value!');
  //   }
  // }
  // add specs function
  public function addrelated()
  {
    $this->showrelatedspecs = true;
    $this->addrelatedspecs = true;
  }
  public function selectSpec($index, $id, $name)
  {
    $this->specsAndValues[$index]['itemselected'] = $name;
    $this->specsAndValues[$index]['spec']['name'] = $id;
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
        $newspec->spec_id = $specAndValue['spec']['name'];
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