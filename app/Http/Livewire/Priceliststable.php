<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PriceList;
use Livewire\WithPagination;
use App\Models\PricelistEntries;

class Priceliststable extends Component
{

  use WithPagination;
  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $itemidbeingremoved = null;
  public $columns = ['Id', 'Currency', 'Active', 'Created At'];
  public $selectedColumns = [];

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
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->pricelistsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getPricelistsProperty()
  {
    return $this->pricelistsQuery->limit($this->loadAmount)->get();
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
      $productpricelists = PricelistEntries::where('pricelist_id', $id)->get();
      if ($productpricelists != NULL) {
        foreach ($productpricelists as $productpricelist) {
          $productpricelist->delete();
        }
      }
      $itemdel = PriceList::find($id);
      $itemdel->delete();
    }
    $this->checked = [];
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteSingleRecord()
  {
    $id = $this->itemidbeingremoved;
    $productpricelists = PricelistEntries::where('pricelist_id', $id)->get();
    if ($productpricelists != NULL) {
      foreach ($productpricelists as $productpricelist) {
        $productpricelist->delete();
      }
    }
    $item = PriceList::findOrFail($id);
    $item->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
