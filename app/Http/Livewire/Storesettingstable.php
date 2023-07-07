<?php

namespace App\Http\Livewire;

use App\Exports\StoreSettingsExport;
use App\Models\Store_Settings;
use Livewire\Component;
use Livewire\WithPagination;

class Storesettingstable extends Component
{

  use WithPagination;
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $itemidbeingremoved = null;
  public $columns = ['Id', 'Value', 'Description', 'Created At'];
  public $selectedColumns = [];
  public $indexstoresettings = null;

  public function render()
  {
    return view('livewire.storesettingstable', [
      'storesettings' => $this->storesettings
    ]);
  }

  public function mount()
  {
    $this->selectedColumns = $this->columns;
  }
  public function showColumn($column)
  {
    if ($column === 'Parameter') {
      return true;
    }
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->storesettings->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->storesettingsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getStoresettingsProperty()
  {
    return $this->storesettingsQuery->paginate($this->perPage);
  }
  public function getStoresettingsQueryProperty()
  {
    return Store_Settings::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function deleteRecords()
  {
    $items = Store_Settings::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemdel = Store_Settings::find($id);
      $itemdel->delete();
    }
    $this->checked = [];
    session()->flash('message', 'Records deleted succesfuly');
  }
  public function deleteSingleRecord()
  {
    $id = $this->itemidbeingremoved;
    $item = Store_Settings::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('message', 'Record deleted Successfully');
  }
  public function confirmItemRemoval($id)
  {
    $this->itemidbeingremoved = $id;
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
    $export = new StoreSettingsExport($this->checked);
    $this->checked = [];
    $this->selectPage = false;
    return $export->download('storesettings.xlsx');
  }
}
