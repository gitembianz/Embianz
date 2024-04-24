<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Sessionstable extends Component
{
    use WithPagination;

    public $tableName;
    public $loadAmount = 10;
    public $columns;
    public $search = '';
    public $selectedColumns;
    public $checked = [];
    public $selectPage = false;
    public $orderBy = 'id';
    public $orderAsc = true;
    public $selectAll = false;
    public $removedid;

    public function render()
    {
        return view('livewire.sessionstable', [
            'sessions' => $this->sessions
        ]);
    }
    public function mount($tableName)
    {
        $this->tableName = $tableName;
        $this->columns = Schema::getColumnListing($this->tableName);
        $this->selectedColumns = $this->columns;
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->sessions->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function swapSortDirection()
    {
        return $this->orderAsc === '1' ? '0' : '1';
    }
    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->sessions->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function getSessionsProperty()
    {
        if (Schema::hasTable($this->tableName)) {
            return DB::table($this->tableName)
                ->where(function ($query) {
                    $query->where('id', 'like', '%' . $this->search . '%')
                        ->orWhere('user_agent', 'like', '%' . $this->search . '%')
                        ->orWhere('payload', 'like', '%' . $this->search . '%')
                        ->orWhere('last_activity', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->limit($this->loadAmount)
                ->get();
        } else {
            return collect();
        }
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
    public function deleteRecords()
    {
        $items = DB::table($this->tableName)->whereIn('id', $this->checked)->get();
        foreach ($items as $item) {
            DB::table($this->tableName)->where('id', $item->id)->delete();
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
        DB::table($this->tableName)->where('id', $this->removedid)->delete();
        $this->checked = array_diff($this->checked, [$this->removedid]);
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function remove($id)
    {
        $this->removedid = $id;
        $this->dispatchBrowserEvent('show-delete-modal-sessions');
    }
    public function confirmItemsRemovalmultiple()
    {
        $this->dispatchBrowserEvent('show-delete-modal-multiple-sessions');
    }
}