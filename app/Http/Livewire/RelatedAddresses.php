<?php

namespace App\Http\Livewire;

use App\Models\Account;
use App\Models\Address;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedAddresses extends Component
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
    public $showrelatedadd = false;
    public $accountId;
    public $col = false;
    public $all = false;
    public $removedid = null;
    public $columns = ['Id', 'First Name', 'Last Name', 'Phone', 'Email', 'Address', 'Optional Address', 'Country', 'County', 'City', 'Post Code', 'Type', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $account;


    //related subcatecory functions
    public function showColumn($column)
    {
        if ($column === 'Id') {
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
            $this->checked = $this->addresses->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
        $this->checked = $this->addressesQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    }
    public function getAddressesProperty()
    {
        return $this->addressesQuery->get();
    }
    public function getAddressesQueryProperty()
    {
        return Address::search($this->search)->where('account_id', $this->accountId)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function confirmItemRemoval($id)
    {
        $this->removedid = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function deleteSingleRecord()
    {
        $record = Address::findOrFail($this->removedid);
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
        $records = Address::whereKey($this->checked)->get();
        foreach ($records as $record) {
            $id = $record->id;
            $recordtodel = Address::find($id);
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
            'addresses' => $this->addresses,
        ]);
    }
    public function mount($accountId)
    {
        $this->accountId = $accountId;
        $this->account = Account::find($accountId);
        $this->selectedColumns = $this->columns;
    }
}
