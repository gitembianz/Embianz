<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class BeeingInOrder extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $relatedby;
    public $productid;
    public $selectedColumns = [];
    public $columns = [];
    public $row = null;
    public $loadAmount = 15;
    public $showrelated = false;

    public function render()
    {
        return view('livewire.beeing-in-order', [
            'orders' => $this->orders
        ]);
    }
    public function mount($productid)
    {
        $this->productid = $productid;
        $this->columns = Schema::getColumnListing('orders');
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
        return Order::with([
            'account',  // Eager load account relationship
            'cart',     // Eager load cart relationship
            'currency', // Eager load currency relationship
            'status',   // Eager load status relationship
            'payment',  // Eager load payment relationship
            'voucher',  // Eager load voucher relationship
        ])
            ->whereHas('orders', function ($query) {
                $query->where('product_id', $this->productid); // Filter by product_id
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc') // Apply ordering
            ->paginate($this->loadAmount); // Paginate the results
    }
}
