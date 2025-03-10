<?php

namespace App\Http\Livewire;

use App\Models\Static_Page;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class Pagestable extends Component
{
    use WithPagination;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $selectedColumns = [];
    public $columns = [];
    public $row = null;
    public $loadAmount = 20;
    public $single = false;
    public $multiple = false;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $idbeingremoved = null;


    public function render()
    {
        return view('livewire.pagestable', [
            'pages' => $this->pages
        ]);
    }
    public function mount($tableName)
    {
        $this->columns = Schema::getColumnListing($tableName);
        $this->selectedColumns = $this->columns;
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
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function swapSortDirection()
    {
        return $this->orderAsc === '1' ? '0' : '1';
    }
    public function sortBy($columnName)
    {
        if ($this->orderBy === $columnName) {
            $this->orderAsc = $this->swapSortDirection();
        } else {
            $this->orderBy = $columnName;
            $this->orderAsc = true;
        }
    }
    public function getPagesProperty()
    {

        return Static_Page::search($this->search)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
            ->paginate($this->loadAmount);
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
}
