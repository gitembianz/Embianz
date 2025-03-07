<?php

namespace App\Http\Livewire;

use App\Models\Order_Item;
use App\Models\Order_Supplier_Item;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class BeeingInOrder extends Component
{
    use WithPagination;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $relatedby;
    public $productid;
    public $selectedColumns = [];
    public $columns = [];
    public $row = null;
    public $loadAmount = 10;
    public $showrelated = false;

    public function render()
    {
        return view('livewire.beeing-in-order', [
            'orders' => $this->orders
        ]);
    }
    public function mount($relatedby, $productid)
    {
        $this->productid = $productid;
        $this->relatedby = $relatedby;
        if ($this->relatedby === 'order') {
            $this->columns = Schema::getColumnListing('order__items');
        } else {
            $this->columns = Schema::getColumnListing('order__supplier__items');
        }
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
            $this->orderAsc = '1';
        }

        $this->orderBy = $columnName;
    }
    public function getOrdersProperty()
    {
        if ($this->relatedby === 'order') {

            return Order_Item::with([
                'order',
                'product'
            ])
                ->where('product_id', $this->productid)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc') // Apply ordering
                ->paginate($this->loadAmount); // Paginate the results
        } elseif ($this->relatedby === 'supplier') {
            return Order_Supplier_Item::with([
                'order',
                'product'
            ])
                ->where('product_id', $this->productid)
                ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc') // Apply ordering
                ->paginate($this->loadAmount); // Paginate the results
        }
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
}
