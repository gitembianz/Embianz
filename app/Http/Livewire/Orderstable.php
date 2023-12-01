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
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $tableName;
    public $columns;
    public $selectedColumns = [];
    public $col = false;
    public $all = false;
    public $itemidbeingremoved = null;

    public function render()
    {
        $orders = $this->orders;
        return view('livewire.orderstable', compact('orders'));
    }
    public function mount($tableName)
    {
        $this->tableName = $tableName;
        $this->columns = Schema::getColumnListing($this->tableName);

        if (session()->has('selectedColumns')) {
            $this->selectedColumns = session('selectedColumns');
        } else {
            $this->selectedColumns = $this->columns;
        }
    }
    public function getOrdersProperty()
    {
        return $this->ordersQuery->limit($this->loadAmount)->get();
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
            $this->checked = $this->orders->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->checked = $this->ordersQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function deleteSingleRecord()
    {
        $id = $this->itemidbeingremoved;
        $item = Order::findOrFail($id);
        $order_items = Order_Item::where('order_id', $id)->get();

        if ($order_items != NULL) {
            foreach ($order_items as $order_item) {

                $order_item->delete();
            }
        }
        $item->delete();
        $this->checked = array_diff($this->checked, [$id]);
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function confirmItemRemoval($id)
    {
        $this->itemidbeingremoved = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function confirmItemsRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal-multiple');
    }
    public function deleteRecords()
    {
        $orders = Order::whereKey($this->checked)->get();
        foreach ($orders as $order) {
            $id = $order->id;
            $item = Order::find($id);
            $order_items = Order_Item::where('order_id', $id)->get();

            if ($order_items != NULL) {
                foreach ($order_items as $order_item) {

                    $order_item->delete();
                }
            }
            $item->delete();
        }
        $this->checked = [];
        $this->selectPage = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
