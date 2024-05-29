<?php

namespace App\Http\Livewire;

use App\Models\CustomScript;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;



class CustomScriptsTable extends Component
{

    use WithPagination;

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
    public $col = false;
    public $all = false;
    public $tableName;
    public $columns;


    public function render()
    {
        return view('livewire.custom-scripts-table', [
            'customscripts' => $this->customscripts
        ]);
    }
    public function mount($tableName)
    {
        $this->tableName = $tableName;
        $this->columns = Schema::getColumnListing($this->tableName);
        $this->selectedColumns = $this->columns;
    }

    public function updatedSelectedColumns()
    {
        session(['selectedColumns' => $this->selectedColumns]);
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->customscripts->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->checked = $this->customscriptsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function getCustomscriptsProperty()
    {
        return CustomScript::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->limit($this->loadAmount)->get();
    }
    public function deleteRecords()
    {
        $categories = CustomScript::whereKey($this->checked)->get();
        foreach ($categories as $category) {
            $id = $category->id;
            $cattodel = CustomScript::find($id);
            $cattodel->delete();
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
        $id = $this->idbeingremoved;
        $category = CustomScript::findOrFail($id);
        $category->delete();
        $this->checked = array_diff($this->checked, [$id]);
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function confirmItemRemoval($id)
    {
        $this->idbeingremoved = $id;
        $this->dispatchBrowserEvent('show-delete-modal-script');
    }
    public function confirmItemsRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal-multiple-script');
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
}
