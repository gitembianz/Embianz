<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use App\Models\Order_Item;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
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
    public $status31Only = false;
    public $xmlinvoicesmodal = false;
    public $xmlstornomodal = false;
    public $filteractive = false;
    public $start_date_filter;
    public $end_date_filter;
    public $start_date;
    public $end_date;

    protected $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    protected $messages = [
        'start_date.required' => 'Start date is required.',
        'end_date.required' => 'The end date is required.',
        'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
    ];

    // Validate and generate XML for invoices
    public function generate_xml_invoice()
    {
        $this->validate();
        // Handle logic to generate XML for invoices
        session()->flash('message', 'XML Invoice generated successfully.');
        $this->resetModal();
    }

    // Validate and generate XML for storno
    public function generate_xml_storno()
    {
        $this->validate();
        // Handle logic to generate XML for storno
        session()->flash('message', 'XML Storno generated successfully.');
        $this->resetModal();
    }

    // Cancel and reset modal
    public function cancel_xml()
    {
        $this->resetModal();
    }

    private function resetModal()
    {
        $this->xmlinvoicesmodal = false;
        $this->xmlstornomodal = false;
        $this->reset(['start_date', 'end_date']);
    }

    public function xmlinvoices()
    {
        $this->xmlinvoicesmodal = true;
    }

    public function filter()
    {
        $this->filteractive = true;
    }

    public function xmlstorno()
    {
        $this->xmlstornomodal = true;
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

    public function cancel_filter()
    {
        $this->filteractive = false;
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
    public function filter_order()
    {
        $this->validate([
            'start_date_filter' => 'nullable|date',
            'end_date_filter' => 'nullable|date|after_or_equal:start_date_filter',
        ]);

        // Set the query to filter by date range
        $this->orders->when($this->start_date_filter && $this->end_date_filter, function ($query) {
            $query->whereBetween('invoice_date', [$this->start_date_filter, $this->end_date_filter]);
        });
        $this->filteractive = false;
    }
    public function getOrdersProperty()
    {
        return $this->ordersQuery->paginate($this->loadAmount);
    }
    public function getOrdersQueryProperty()
    {
        $query = Order::search($this->search)
            ->with([
                'orders.product' => function ($query) {
                    $query->withCount(['orders_item as interim_quantity' => function ($query) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status_id', 31);
                        })->select(DB::raw('sum(quantity)'));
                    }]);
                },
                'status',
                'account',
                'cart',
                'currency',
                'voucher',
                'payment'
            ])
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');

        if ($this->status31Only) {
            $query = $query->where('status_id', 31);
        }

        if ($this->start_date_filter && $this->end_date_filter) {
            $query = $query->whereBetween('invoice_date', [$this->start_date_filter, $this->end_date_filter]);
        }

        return $query;
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
