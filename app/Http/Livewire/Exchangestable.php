<?php

namespace App\Http\Livewire;

use App\Models\Exchange;
use Livewire\Component;
use Livewire\WithPagination;


class Exchangestable extends Component
{
    use WithPagination;
    public $loadAmount = 30;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $itemidbeingremoved = null;
    public $columns = ['id', 'base_currency_id', 'quote_currency_id', 'value', 'created_by', 'last_modified_by', 'last_modified_date', 'created_at', 'updated_at'];
    public $selectedColumns = [];
    public $rowindex = null;

    public function render()
    {
        return view('livewire.exchangestable', [
            'exchanges' => $this->exchanges
        ]);
    }
    public function mount()
    {
        $this->selectedColumns = $this->columns;
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
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
    public function getExchangesProperty()
    {
        return Exchange::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount);
    }
}