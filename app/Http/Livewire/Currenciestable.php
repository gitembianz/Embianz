<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class Currenciestable extends Component
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
    public $editindex = null;
    public $details = [];
    public $row = null;

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

    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function render()
    {

        return view('livewire.currenciestable', [
            'currencies' => $this->currencies
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
        if ($column === 'name') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->currencies->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->checked = $this->currenciesQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }

    public function getCurrenciesProperty()
    {
        return Currency::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount);
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
    public function edit($index, $id)
    {
        $this->editindex = $index;
        $item = Currency::find($id);
        $this->details = [
            $index . '.name' => $item->name,
            $index . '.symbol' => $item->symbol
        ];
    }
    public function cancel()
    {
        $this->editindex = null;
        $this->details = [];
    }
    public function save($index, $id)
    {
        $new = $this->details[$index] ?? NULL;

        if (!is_null($new)) {
            $item = Currency::find($id);
            if (array_key_exists('name', $new)) {

                $item->name = $new['name'];
            }
            if (array_key_exists('symbol', $new)) {

                $item->symbol = $new['symbol'];
            }
            $item->save();

            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }

        $this->details = [];
        $this->editindex = null;
    }
}
