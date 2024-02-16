<?php

namespace App\Http\Livewire;

use App\Models\Account;
use App\Models\Address;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class Accountstable extends Component
{
    use WithPagination;
    public $loadAmount = 20;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $removedid = null;
    public $selectedColumns = [];
    public $col = false;
    public $all = false;
    public $tableName;
    public $columns;

    public function render()
    {
        $accounts = $this->accounts;

        if ($this->all) {
            $this->selectedColumns = $this->columns;
        }

        return view('livewire.accountstable', compact('accounts'));
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
    public function updatedSelectedColumns()
    {
        session(['selectedColumns' => $this->selectedColumns]);
    }
    public function showColumn($column)
    {
        if ($column === 'name') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->accounts->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->checked = $this->accountsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function getAccountsProperty()
    {
        return $this->accountsQuery->limit($this->loadAmount)->get();
    }
    public function loadMore()
    {
        $this->loadAmount += 10;
    }
    public function getAccountsQueryProperty()
    {
        return Account::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function deleteRecords()
    {
        $accounts = Account::whereKey($this->checked)->get();
        foreach ($accounts as $account) {
            $del = Account::find($account->id);
            $adresses = Address::where('account_id', $del->id)->get();
            if ($adresses != NULL) {
                foreach ($adresses as $adress) {
                    $adress->delete();
                }
            }
            $orders = Order::where('account_id', $account->id)->get();
            if ($orders != NULL) {
                foreach ($orders as $order) {
                    $order->account_id = null;
                    $order->save();
                }
            }

            $del->delete();
        }
        $this->checked = [];
        $this->selectPage = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteSingleRecord()
    {
        $account = Account::findOrFail($this->removedid);
        $adresses = Address::where('account_id', $account->id)->get();
        if ($adresses != NULL) {
            foreach ($adresses as $adress) {
                $adress->delete();
            }
        }
        $orders = Order::where('account_id', $account->id)->get();
        if ($orders != NULL) {
            foreach ($orders as $order) {
                $order->account_id = null;
                $order->save();
            }
        }
        $account->delete();
        $this->checked = array_diff($this->checked, [$this->removedid]);
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function confirmItemRemoval($id)
    {
        $this->removedid = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function confirmItemsRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal-multiple');
    }
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
}
