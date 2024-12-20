<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Order_Item;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedOrderItems extends Component
{
    use WithPagination;
    //related delclaration/
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $showrelatedprod = false;
    public $orderId;
    public $col = false;
    public $all = false;
    public $idbeingremoved = null;
    public $columns = ['Id', 'Price', 'Quantity', 'VAT'];
    public $selectedColumns = [];
    public $order;
    public $row = null;
    public $single = false;
    public $multiple = false;

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
        $orderproducts = $this->orderproducts
            ->where(function ($query) {
                $query->whereHas('product', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->get();


        return view('livewire.related-order-items', [
            'orderproducts' => $orderproducts,
        ]);
    }
    public function mount(Order $order)
    {
        $this->orderId = $order->id;
        $this->order = $order;
        $this->selectedColumns = $this->columns;
    }
    //function for related products
    public function showColumn($column)
    {
        if ($column === 'Product') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->orderproducts->pluck('id')->map(fn($item) => (string) $item)->toArray();
        } else {
            $this->checked = [];
        }
    }
    public function swapSortDirection()
    {
        return $this->orderAsc === '1' ? '0' : '1';
    }
    public function updatedChecked()
    {
        $this->selectPage = false;
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
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
    public function selectAll()
    {
        $this->selectAll = true;
        $this->checked = $this->orderproductsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function load()
    {
        $this->perPage += 10;
    }
    public function getOrderproductsProperty()
    {
        return $this->orderproductsQuery;
    }
    public function getOrderproductsQueryProperty()
    {
        return Order_Item::where('order_id', $this->orderId)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function deleteSingleRecord()
    {
        $id = $this->idbeingremoved;
        $item = Order_Item::findOrFail($id);
        $this->order->quantity_amount -= $item->quantity;
        $this->order->save();
        $item->delete();
        $this->checked = array_diff($this->checked, [$id]);
        $this->single = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->emit('orderUpdated');
    }
    public function deleteRecords()
    {
        $items = Order_Item::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $id = $item->id;
            $del = Order_Item::find($id);
            $this->order->quantity_amount -= $del->quantity;
            $this->order->save();
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
        $this->emit('orderUpdated');
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
}
