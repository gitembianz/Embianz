<?php

namespace App\Http\Livewire;

use App\Models\Store_Settings;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;



class Storesettingstable extends Component
{

  use WithPagination;
  public $loadAmount = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $itemidbeingremoved = null;
  public $columns = ['Id', 'Value', 'Description', 'Created At', 'Updated At'];
  public $selectedColumns = [];
  public $indexstoresettings = null;
  public $settings = [];

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
  public function actualizeaza()
  {
    Artisan::call('cache:clear');
    Artisan::call('clear-compiled');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('event:clear');
    Artisan::call('queue:clear');
    Artisan::call('optimize:clear');
    Artisan::call('migrate');
    Cache::forget('global_variables');
    Cache::forget('global_statuses');
    Cache::forget('global_payments');
    session()->flash('notification', [
      'message' => 'Website is updated!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->storesettings->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
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
    return $this->storesettingsQuery->limit($this->loadAmount)->get();
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
    $item = Store_Settings::findOrFail($id);
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
  public function edititem($index, $id)
  {
    $record = Store_Settings::find($id);
    $this->indexstoresettings = $index;
    $this->settings = [
      $index . '.value' => $record->value,
      $index . '.description' => $record->description,
    ];
  }
  public function saveitem($index, $id)
  {
    $update = $this->settings[$index] ?? NULL;
    if (!is_null($update)) {
      $item = Store_Settings::find($id);
      if (array_key_exists('value', $update)) {
        $item->value = $update['value'];
      }
      if (array_key_exists('description', $update)) {
        $item->description = $update['description'];
      }
      $item->save();
      // Correct cache key construction
      Cache::forget('global_variables');
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      session()->flash('notification', [
        'message' => 'Nothing chnaged!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
    $this->settings = [];
    $this->indexstoresettings = null;
  }
  public function cancelitem()
  {
    $this->indexstoresettings = null;
    $this->settings = [];
  }
}
