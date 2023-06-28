<?php

namespace App\Http\Livewire;

use App\Exports\SpecsExport;
use App\Models\Specs;
use Livewire\Component;
use Livewire\WithPagination;

class Specstable extends Component
{

  use WithPagination;
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $specidbeingremoved = null;
  public $columns = ['Id', 'Name', 'Unit', 'Created At'];
  public $selectedColumns = [];

    public function render()
    {
        return view('livewire.specstable', [
          'specs' => $this->specs
        ]);
    }

    public function mount()
  {
    $this->selectedColumns = $this->columns;
  }
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
    return $this->specsQuery->paginate($this->perPage);
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
      $specdel = Specs::find($id);
      $specdel->delete();
    }
    $this->checked = [];
    session()->flash('message', 'Records deleted succesfuly');
  }

  public function deleteSingleRecord()
  {
    $id = $this->specidbeingremoved;
    $item = Specs::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('message', 'Record deleted Successfully');
  }
  public function confirmItemRemoval($id)
  {
    $this->specidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
  }

  public function confirmItemsRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }

  public function exportSelected()
  {
    $export = new SpecsExport($this->checked);
    $this->checked = [];
    $this->selectPage = false;
    return $export->download('specs.xlsx');
  }
}
