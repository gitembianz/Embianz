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
  public $col = false;
  public $all = false;
  public $columns = ['Id', 'Unit', 'Value', 'Sequence', 'Created At'];
  public $selectedColumns = [];
  public $specidbeingremoved = null;
  public $addrelatedspecs = false;
  public $itemselected;
  public $isselected;
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
    $relatedspecs = $this->relatedspecsQuery
      ->where(function ($query) {
        $query->whereHas('spec', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('um', 'LIKE', '%' . $this->search . '%');
        });
      })->get();

    return view('livewire.related-spec-product', [
      'relatedspecs' => $relatedspecs,
      'addspecs' => $this->addspecs,
    ]);
  }
  public function mount($product)
  {
    $this->selectedColumns = $this->columns;
    $this->item = $product;
    $this->specsAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'spec' => ['idrel' => null, 'value' => null, 'sequence' => null],
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
      $this->checked = $this->relatedspecs->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->relatedspecsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getRelatedspecsProperty()
  {
    return $this->relatedspecsQuery;
  }
  public function getRelatedspecsQueryProperty()
  {
    return Product_Spec::where('product_id', $this->item->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('spec');
  }
  public function confirmRemoval($id)
  {
    $this->specidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-spec');
  }
  public function deleteSingleRecord()
  {
    $id = $this->specidbeingremoved;
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
      'message' => 'Records  deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
      $index . '.sequence' => $val->sequence,

    ];
  }
  public function canceledit()
  {
    $this->editedrow = null;
    $this->allow = false;
    $this->itemselected = null;
    $this->specification = [];
  }
  public function confirmspecs($index, $id)
  {
    $newspec = Product_Spec::find($id);
    if ($this->isselected != null) {
      if ($this->isselected) {
        $newspec->spec_id = $this->specid;
        $newspec->save();
      } else {
        session()->flash('notification', [
          'message' => 'Please provide a value!',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
      }
    }
    $val = $this->specification;
    if (isset($val["$index"]['value']) || isset($val["$index"]['sequence'])) {
      if (isset($val["$index"]['value']) && $val["$index"]['value'] != "") {
        $newspec->value = $val["$index"]['value'];
      }
      if (isset($val["$index"]['sequence']) && $val["$index"]['sequence'] != "") {

        $newspec->sequence = $val["$index"]['sequence'];
      }

      $newspec->save();
      $this->allow = false;
      $this->specid = null;
      $this->specification = [];
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
      $this->specid = null;
      $this->specification = [];
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
  public function editSelected()
  {
    $this->itemstoedit = $this->checked;
    $this->editmultiple = true;

    foreach ($this->itemstoedit as $index => $item) {
      $test = Product_Spec::find($item);

      if ($test && $test->spec) {
        $this->specsAndValues[$index]['itemselected'] = $test->spec->name;
        $this->specsAndValues[$index]['spec']['id'] = $test->id;
        $this->specsAndValues[$index]['spec']['idrel'] = $test->spec->id;
        $this->specsAndValues[$index]['spec']['value'] = $test->value;
        $this->specsAndValues[$index]['spec']['sequence'] = $test->sequence;

        $this->specsAndValues[$index]['allow'] = false;
      }
    }
  }
  public function confirmspecsmultiple()
  {

    if (empty($this->specsAndValues)) {
      session()->flash('notification', [
        'message' => 'No specifications to update.',
        'type' => 'warning',
        'title' => 'No Data'
      ]);
      return;
    }

    foreach ($this->specsAndValues as  $specAndValue) {
      if (!empty($specAndValue['spec']['value']) || !empty($specAndValue['spec']['sequence'])) {
        $spec = Product_Spec::find($specAndValue['spec']['id']);
        if ($spec) {
          $spec->spec_id = $specAndValue['spec']['idrel'];
          $spec->value = $specAndValue['spec']['value'];
          $spec->sequence = $specAndValue['spec']['sequence'];
          $spec->save();
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

    $this->specsAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'spec' => ['name' => null, 'value' => null, 'sequence' => null],
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
  }
  // add specs function
  public function addrelated()
  {
    $this->showrelatedspecs = true;
    $this->addrelatedspecs = true;
  }
  public function denny()
  {

    $this->allow = false;
  }
  public function select($id)
  {
    $this->itemselected = Specs::find($id);
    $this->specid = $id;
    $this->allow = false;
    $this->isselected = true;
  }
  public function selectSpec($index, $id, $name)
  {
    $this->specsAndValues[$index]['itemselected'] = $name;
    $this->specsAndValues[$index]['spec']['idrel'] = $id;
    $this->specsAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function plus()
  {
    $this->row++;
    $this->specsAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'spec' => ['name' => null, 'value' => null, 'sequence' => null],
    ];
  }
  public function allowselect($index)
  {

    foreach ($this->specsAndValues as &$item) {
      $item['allow'] = false;
    }
    $this->specsAndValues[$index]['allow'] = true;
    $this->searchadd = $this->specsAndValues[$index]['itemselected'];
  }
  public function dennyselect($index)
  {
    $this->specsAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function allow()
  {
    $this->allow = true;
    $this->isselected = false;
    $this->searchadd = $this->itemselected->name;
  }
  public function closemodal()
  {
    $this->specsAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'spec' => ['name' => null, 'value' => null, 'sequence' => null],
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
    unset($this->specsAndValues[$index]);
    $this->specsAndValues = array_values($this->specsAndValues);

    // Decrement the total row count
    $this->row--;
    if ($this->row < 1) {
      $this->addrelatedspecs = false;
      $this->specsAndValues = [
        [
          'allow' => false,
          'itemselected' => null,
          'spec' => ['name' => null, 'value' => null, 'sequence' => null],
        ]
      ];
      $this->row = 1;
    }
  }
  public function savespecs()
  {
    $empty = false;
    foreach ($this->specsAndValues as  $specAndValue) {
      $val = $specAndValue['spec'];
      if (array_key_exists('value', $val) && $specAndValue['spec']['value'] == null && array_key_exists('sequence', $val)) {
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
      foreach ($this->specsAndValues as  $specAndValue) {
        $val = $specAndValue['spec'];
        $newspec = new Product_Spec();
        $newspec->product_id = $this->item->id;
        $newspec->spec_id = $specAndValue['spec']['idrel'];
        $newspec->value = $specAndValue['spec']['value'];
        $newspec->sequence = $specAndValue['spec']['sequence'];

        $newspec->save();
      }
    }


    $this->specsAndValues = [
      [
        'allow' => false,
        'itemselected' => null,
        'spec' => ['name' => null, 'value' => null, 'sequence' => null],
      ]
    ];
    $this->row = 1;
    $this->addrelatedspecs = false;
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function getAddspecsProperty()
  {
    $ids = $this->relatedspecs->pluck('spec_id')->toArray();
    $unrelated = Specs::whereNotIn('id', $ids);
    if (!empty($this->searchadd)) {
      $unrelated->where('name', 'like', '%' . $this->searchadd . '%');
    }
    $unrelated->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
    return $unrelated->get();
  }
}
