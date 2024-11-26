<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;


class Brandstable extends Component
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
    public $tableName;
    public $columns;
    public $row = null;
    public $single = false;
    public $multiple = false;
    public $addbrand = false;
    public $rowadd;
    public $rind2 = null;
    public $brand_description = [];
    public $brand_name = [];



    public function render()
    {
        return view('livewire.brandstable', ['brands' => $this->brands]);
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
        $this->rowadd = 0;
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
            $this->checked = $this->brands->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->brandsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function getBrandsProperty()
    {
        return $this->brandsQuery->paginate($this->loadAmount);
    }
    public function getBrandsQueryProperty()
    {
        return Brand::search($this->search)
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
        $items = Brand::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $del = Brand::findOrFail($item->id);
            $medias = $del->media()->get();
            foreach ($medias as $media) {
                $media->delete();
            }
            $productType = class_basename(get_class($item));
            $filespath = 'media/' . $productType . '/' . $item->id;
            if (File::exists($filespath)) {
                File::deleteDirectory($filespath);
            }
            $del->delete();
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
        $item = Brand::findOrFail($this->idbeingremoved);
        $medias = $item->media()->get();
        foreach ($medias as $media) {
            $media->delete();
        }
        $productType = class_basename(get_class($item));
        $filespath = 'media/' . $productType . '/' . $item->id;
        if (File::exists($filespath)) {
            File::deleteDirectory($filespath);
        }
        $item->delete();
        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
        $this->single = false;

        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function plus()
    {
        $this->rowadd++;
        $this->brand_name[$this->rowadd] = null;
        $this->brand_description[$this->rowadd] = null;
    }
    public function clear($i)
    {
        array_splice($this->brand_name, $i, 1);
        array_splice($this->brand_description, $i, 1);
        $this->rowadd--;

        if ($this->rowadd < 0) {
            $this->addbrand = false;
            $this->rowadd = 0;
            $this->brand_name = [];
            $this->brand_description = [];
        }
    }
    public function save_brands()
    {
        for ($i = 0; $i <= $this->rowadd; $i++) {
            $this->resetErrorBag();
            $this->validate([
                'brand_name.*' => 'required',
                'brand_description.*' => 'required'
            ]);
            // de pus eorile in blade
            Brand::create([
                'name' => $this->brand_name[$i],
                'description' => $this->brand_description[$i]
            ]);
        }
        session()->flash('notification', [
            'message' => 'Brands added successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}