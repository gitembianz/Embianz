<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;


class Paymentstable extends Component
{
    use WithPagination;
    public $loadAmount = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $removedid = null;
    public $tableName;
    public $columns;
    public $selectedColumns = [];
    public $editeindex = null;
    public $isactive = [];

    public function render()
    {
        return view('livewire.paymentstable', [
            'payments' => $this->payments
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
            $this->checked = $this->payments->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->checked = $this->paymentsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function getPaymentsProperty()
    {
        return Payment::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->limit($this->loadAmount)->get();
    }
    public function deleteRecords()
    {
        $items = Payment::whereKey($this->checked)->get();

        foreach ($items as $item) {
            $del = Payment::find($item->id);
            $del->delete();
        }
        $this->selectPage = false;
        $this->checked = [];
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteSingleRecord()
    {
        $item = Payment::findOrFail($this->removedid);
        $item->delete();
        $this->checked = array_diff($this->checked, [$this->removedid]);
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function confirmItemRemoval($id)
    {
        $this->removedid = $id;
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
    public function edit($index, $id)
    {
        $this->editeindex = $index;
        $item = Payment::find($id);
        $this->isactive = [
            $index . '.active' => $item->active == 1 ? true : false,
        ];
    }
    public function cancel()
    {
        $this->editeindex = null;
        $this->isactive = [];
    }
    public function save($index, $id)
    {
        $new = $this->isactive[$index] ?? NULL;

        if (!is_null($new) && array_key_exists('active', $new)) {
            $item = Payment::find($id);
            $item->active = $new['active'] ? 1 : 0; // Convert true to 1 and false to 0
            $item->save();

            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }

        $this->isactive = [];
        $this->editeindex = null;
    }
}
