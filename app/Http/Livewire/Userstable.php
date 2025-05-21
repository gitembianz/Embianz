<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Schema;
use Livewire\WithPagination;

class Userstable extends Component
{
    public $selectedColumns = [];
    public $columns;
    public $row = null;
    public $single = false;
    public $multiple = false;
    public $loadAmount = 20;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = false;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $idbeingremoved = null;

    public function render()
    {
        return view('livewire.userstable',[
          'users' => $this->users,
        ]);
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
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
    public function mount($tableName)
    {
        $this->columns = Schema::getColumnListing($tableName);

        $this->selectedColumns = $this->columns;
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->categories->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->usersQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function getUsersProperty()
    {
        return $this->usersQuery->paginate($this->loadAmount);
    }

    public function getUsersQueryProperty()
    {
        return User::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
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
