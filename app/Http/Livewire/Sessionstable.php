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
    public $loadAmount = 20;
    public $columns;
    public $search = '';
    public $selectedColumns;
    public $checked = [];
    public $selectPage = false;
    public $orderBy = 'id';
    public $orderAsc = true;
    public $selectAll = false;
    public $idbeingremoved = null;
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
            $this->checked = $this->sessions->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->sessions->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function getSessionsProperty()
    {
        if (Schema::hasTable($this->tableName)) {
            return DB::table($this->tableName)
                ->where(function ($query) {
                    $query->where('id', 'like', '%' . $this->search . '%')
                        ->orWhere('user_agent', 'like', '%' . $this->search . '%')
                        ->orWhere('last_activity', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
                ->paginate($this->loadAmount);
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
        $this->multiple = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteSingleRecord()
    {
        DB::table($this->tableName)->where('id', $this->idbeingremoved)->delete();
        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
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
}
