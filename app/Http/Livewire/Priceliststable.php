<?php

namespace App\Http\Livewire;

use App\Models\PriceList;
use Livewire\Component;
use Livewire\WithPagination;

class Priceliststable extends Component
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
  public $columns = ['Id', 'Currency', 'Created At'];
  public $selectedColumns = [];
  public $indexprice = null;
  public $prices = [];

  public function render()
  {
    return view('livewire.priceliststable', [
      'pricelists' => $this->pricelists
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
      $this->checked = $this->pricelists->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->pricelistsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getPricelistsProperty()
  {
    return $this->pricelistsQuery->paginate($this->perPage);
  }
  public function getPricelistsQueryProperty()
  {
    return PriceList::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('currency');
  }
  public function deleteRecords()
  {
    $items = PriceList::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemdel = PriceList::find($id);
      $itemdel->delete();
    }
    $this->checked = [];
    session()->flash('message', 'Records deleted succesfuly');
  }
  public function deleteSingleRecord()
  {
    $id = $this->itemidbeingremoved;
    $item = PriceList::findOrFail($id);
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
}
