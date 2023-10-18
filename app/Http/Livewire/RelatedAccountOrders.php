<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Account;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedAccountOrders extends Component
{
    use WithPagination;

    //related delclaration
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $showrelatedords = false;
    public $accountId;
    public $col = false;
    public $all = false;
    public $removedid = null;
    public $columns = ['Id', 'Session Id', 'Cart', 'Quantity Amount', 'Sum Amount', 'Currency', 'Status', 'Delivery Method', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $account;


    //related subcatecory functions
    public function showColumn($column)
    {
        if ($column === 'Name') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function load()
    {
        $this->perPage += 10;
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->orders->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        return in_array(
            $id,
            $this->checked
        );
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
        $this->checked = $this->ordersQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function getOrdersProperty()
    {
        return $this->ordersQuery->get();
    }
    public function getOrdersQueryProperty()
    {
        return Order::where('account_id', $this->accountId)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function confirmItemRemoval($id)
    {
        $this->removedid = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function deleteSingleRecord()
    {
        $record = Order::findOrFail($this->removedid);
        $record->delete();
        $this->checked = array_diff($this->checked, [$this->removedid]);
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteRecords()
    {
        $records = Order::whereKey($this->checked)->get();
        foreach ($records as $record) {
            $id = $record->id;
            $recordtodel = Order::find($id);
            $recordtodel->delete();
        }

        $this->checked = [];
        $this->selectPage = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function confirmItemsRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal-multiple');
    }
    public function render()
    {
        return view('livewire.related-addresses', [
            'orders' => $this->orders,
        ]);
    }
    public function mount($accountId)
    {
        $this->accountId = $accountId;
        $this->account = Account::find($accountId);
        $this->selectedColumns = $this->columns;
    }
}
