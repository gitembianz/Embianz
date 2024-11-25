<?php

namespace App\Http\Livewire;

use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;


class Promotiontable extends Component
{
    use WithPagination;
    public $loadAmount = 20;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $idbeingremoved = null;
    public $selectedColumns = [];
    public $columns;
    public $row = null;
    public $single = false;
    public $multiple = false;

    public function render()
    {
        return view('livewire.promotiontable', ['promotions' => $this->promotions]);
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
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->promotions->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->promotionsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function getPromotionsProperty()
    {
        return $this->promotionsQuery->paginate($this->loadAmount);
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function getPromotionsQueryProperty()
    {
        return Promotion::search($this->search)
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

    public function deleteRecords()
    {
        $items = Promotion::whereKey($this->checked)->get();
        foreach ($items as $item) {
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
    public function deleteSingleRecord()
    {
        $id = $this->idbeingremoved;
        $item = Promotion::findOrFail($id);

        $item->delete();
        $this->checked = array_diff($this->checked, [$id]);
        $this->single = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}