<?php

namespace App\Http\Livewire;

use App\Models\ProductVariant;
use App\Models\Variant;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;


class Variantstable extends Component
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
    public $editindex;
    public $variant = [];
    public $statuses;
    public $row = null;
    public $single = false;
    public $multiple = false;
    public $idbeingremoved = null;
    public $count = 0;

    public function render()
    {
        return view('livewire.variantstable', ['variants' => $this->variants]);
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
    public function getVariantsProperty()
    {
        return $this->variantsQuery->paginate($this->loadAmount);
    }
    public function getVariantsQueryProperty()
    {
        return Variant::search($this->search)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->variants->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->editindex = $index;
        $this->expandRow($index);
        $record = Variant::find($id);
        $this->variant = [
            $index . '.name' => $record->name,
            $index . '.sequence' => $record->sequence,
        ];
    }
    public function saveitem($index, $id)
    {
        $record = $this->variant[$index] ?? null;
        if (!is_null($record)) {
            $new = Variant::find($id);
            if (array_key_exists('name', $record)) {
                $new->name = $record['name'];
            }
            if (array_key_exists('sequence', $record)) {
                $new->sequence = $record['sequence'];
            }
            $new->save();
            $this->row = null;

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
        $this->editindex = null;
        $this->variant = [];
    }
    public function canceledit()
    {
        $this->editindex = null;
        $this->variant = [];
        $this->row = null;
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
        $this->checked = $this->variantsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function deleteSingleRecord()
    {
        $id = $this->idbeingremoved;
        $item = Variant::findOrFail($id);
        $productsvariants = ProductVariant::where('variant_id', $id)->get();
        foreach ($productsvariants as $product) {
            $product->delete();
        }
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
        $this->count = ProductVariant::where('variant_id', $id)->count();
    }
    public function confirmItemsRemoval()
    {
        $this->multiple = true;
        $items = Variant::whereIn('id', $this->checked)->get();
        foreach ($items as $item) {
            $this->count  += ProductVariant::where('variant_id', $item->id)->count();
        }
    }
    public function cancel_delete()
    {
        $this->multiple = false;
        $this->single = false;
    }
    public function deleteRecords()
    {

        $items = Variant::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $id = $item->id;
            $productsvariants = ProductVariant::where('variant_id', $id)->get();
            foreach ($productsvariants as $product) {
                $product->delete();
            }
            $item = Variant::find($id);
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