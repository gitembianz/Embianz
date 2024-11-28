<?php

namespace App\Http\Livewire;

use App\Models\Status;
use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;


class Vouchertable extends Component
{
  use WithPagination;
  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $columns;
  public $selectedColumns = [];
  public $editindex = null;
  public $voucher = [];
  public $statuses;
  public $row = null;
  public $single = false;
  public $multiple = false;
  public $idbeingremoved = null;

  public function render()
  {
    return view('livewire.vouchertable', [
      'vouchers' => $this->vouchers
    ]);
  }
  public function expandRow($index)
  {
    if ($this->editindex === $index) {
      return;
    } else {

      if ($this->row  === null) {
        $this->row = $index;
      } elseif ($this->row != $index) {
        $this->row = $index;
      } else {
        $this->row = null;
      }
    }
  }
  public function mount($tableName)
  {
    $this->columns = Schema::getColumnListing($tableName);
    $this->selectedColumns = $this->columns;
  }
  public function getVouchersProperty()
  {
    return $this->vouchersQuery->paginate($this->loadAmount);
  }
  public function getVouchersQueryProperty()
  {
    return Voucher::search($this->search)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('status');
  }
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->vouchers->pluck('id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function edititem($index, $id)
  {
    $this->statuses = Status::where('type', 'voucher')->get();
    $this->editindex = $index;
    $this->row = $index;
    $record = Voucher::find($id);
    $this->voucher = [
      $index . '.name' => $record->name,
      $index . '.code' => $record->code,
      $index . '.percent' => $record->percent,
      $index . '.value' => $record->value,
      $index . '.status_id' => $record->status->name,
      $index . '.single_use' => $record->single_use == 1 ? true : false,
      $index . '.start_date' => $record->start_date,
      $index . '.end_date' => $record->end_date,
    ];
  }
  public function saveitem($index, $id)
  {
    $record = $this->voucher[$index] ?? null;
    if (!is_null($record)) {
      $new = Voucher::find($id);
      if (array_key_exists('name', $record)) {
        $new->name = $record['name'];
      }
      if (array_key_exists('code', $record)) {
        $new->code = $record['code'];
      }
      if (array_key_exists('percent', $record)) {
        $new->percent = $record['percent'];
        $new->value = null;
      }
      if (array_key_exists('value', $record)) {
        $new->value = $record['value'];
        $new->percent = null;
      }
      if (array_key_exists('status_id', $record)) {
        $new->status_id = $record['status_id'];
      }
      if (array_key_exists('single_use', $record)) {
        $new->single_use = $record['single_use'];
      }
      if (array_key_exists('start_date', $record)) {
        $new->start_date = $record['start_date'];
      }
      if (array_key_exists('end_date', $record)) {
        $new->end_date = $record['end_date'];
      }
      $new->save();
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      session()->flash('notification', [
        'message' => 'Nothing was edited!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
    }
    Cache::forget('promotions');
    $this->editindex = null;
    $this->voucher = [];
  }
  public function canceledit()
  {
    $this->editindex = null;
    $this->voucher = [];
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
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->vouchersQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function deleteSingleRecord()
  {
    $id = $this->idbeingremoved;
    $item = Voucher::findOrFail($id);
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
  public function deleteRecords()
  {
    $items = Voucher::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $item = Voucher::find($id);
      $item->delete();
    }
    $this->checked = [];
    $this->selectPage = false;
    $this->multiple = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
