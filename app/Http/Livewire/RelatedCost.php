<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\ProductCost;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class RelatedCost extends Component
{
    use WithPagination;
    public $productid;
    public $selectedColumns = [];
    public $columns = [];
    public $showrelated = false;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $loadAmount = 20;
    public $checked = [];
    public $editindex = null;
    public $selectPage = false;
    public $selectAll = false;
    public $record = [];

    public function render()
    {
        $costs = $this->costs
            ->where(function ($query) {
                $query->whereHas('product', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->paginate($this->loadAmount);
        return view('livewire.related-cost', [
            'costs' => $costs
        ]);
    }
    public function mount($productid, $tableName)
    {
        $this->productid = $productid;
        $this->columns = Schema::getColumnListing($tableName);
        $this->selectedColumns = $this->columns;
    }
    public function getCostsProperty()
    {
        return ProductCost::where('product_id', $this->productid)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('product');
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
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
    public function edititem($index, $id)
    {
        $this->editindex = $index;
        $record = ProductCost::find($id);
        $this->record[$index] = [
            'price' => $record->price,
            'cost' => $record->cost,
            'date' => $record->date,
        ];
    }
    public function canceledit()
    {
        $this->editindex = null;
        $this->record = [];
    }
}