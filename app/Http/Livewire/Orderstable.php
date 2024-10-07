<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Order_Item;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class Orderstable extends Component
{
    use WithPagination;
    public $loadAmount = 20;
    public $search = '';
    public $orderBy = 'created_at';
    public $orderAsc = false;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $tableName;
    public $columns;
    public $selectedColumns = [];
    public $idbeingremoved = null;
    public $single = false;
    public $multiple = false;
    public $row = null;

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

    public function render()
    {
        return view('livewire.orderstable', ['orders' => $this->orders]);
    }
    public function mount($tableName)
    {
        $this->tableName = $tableName;
        $this->columns = Schema::getColumnListing($this->tableName);
        $this->selectedColumns = $this->columns;
    }
    public function getOrdersProperty()
    {
        return $this->ordersQuery->paginate($this->loadAmount);
    }
    public function getOrdersQueryProperty()
    {
        return Order::search($this->search)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function showColumn($column)
    {
        if ($column === 'id') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->orders->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->ordersQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function deleteSingleRecord()
    {
        $id = $this->idbeingremoved;
        $order = Order::findOrFail($id);
        foreach ($order->orders as $orderitem) {
            $orderitem->product->quantity += $orderitem->quantity;
            $orderitem->product->save();
            $orderitem->delete();
        }

        $order->delete();
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
    public function deleteRecords()
    {
        $orders = Order::whereKey($this->checked)->get();
        foreach ($orders as $order) {
            foreach ($order->orders as $orderitem) {
                $orderitem->product->quantity += $orderitem->quantity;
                $orderitem->product->save();
                $orderitem->delete();
            }

            $order->delete();
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