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
    public $idbeingremoved = null;
    public $selectedColumns = [];
    public $tableName;
    public $columns;
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
        return view('livewire.accountstable', ['accounts' => $this->accounts]);
    }
    public function mount($tableName)
    {
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
            $this->checked = $this->accounts->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->accountsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function getAccountsProperty()
    {
        return $this->accountsQuery->paginate($this->loadAmount);
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
        $this->multiple = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function deleteSingleRecord()
    {
        $account = Account::findOrFail($this->idbeingremoved);
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
        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
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
    public function isChecked($id)
    {
        return in_array($id, $this->checked);
    }
}
