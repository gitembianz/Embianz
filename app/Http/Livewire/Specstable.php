<?php

namespace App\Http\Livewire;

use App\Models\Specs;
use App\Models\Product_Spec;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;


class Specstable extends Component
{
  use WithPagination;
  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $idbeingremoved = null;
  public $columns = [];
  public $selectedColumns = [];
  public $tableName;
  public $single = false;
  public $multiple = false;
  public $row = null;

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

  public function render()
  {
    return view('livewire.specstable', [
      'specs' => $this->specs
    ]);
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function mount($tableName)
  {
    $this->tableName = $tableName;
    $this->columns = Schema::getColumnListing($this->tableName);
    $this->selectedColumns = $this->columns;
  }
  public function showColumn($column)
  {
    if ($column === 'name') {
      return true;
    }
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->specs->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
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
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->specsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getSpecsProperty()
  {
    return $this->specsQuery->paginate($this->loadAmount);
  }
  public function getSpecsQueryProperty()
  {
    return Specs::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function deleteRecords()
  {
    $items = Specs::whereKey($this->checked)->get();

    foreach ($items as $item) {
      $id = $item->id;
      $relateds = Product_Spec::where('spec_id', $id)->get();
      if ($relateds != NULL) {
        foreach ($relateds as $rel) {
          $rel->delete();
        }
      }
      $specdel = Specs::find($id);
      $specdel->delete();
    }
    $this->selectPage = false;
    $this->checked = [];
    $this->multiple = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteSingleRecord()
  {
    $id = $this->idbeingremoved;
    $item = Specs::findOrFail($id);
    $relateds = Product_Spec::where('spec_id', $id)->get();
    if ($relateds != NULL) {
      foreach ($relateds as $rel) {
        $rel->delete();
      }
    }
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    $this->single = false;
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
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
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
}
