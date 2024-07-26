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
    public $idbeingremoved = null;
    public $columns = ['Id', 'First Name', 'Last Name', 'Phone', 'Email', 'Address', 'Optional Address', 'Country', 'County', 'City', 'Post Code', 'Type', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $account;
    public $adress = [];
    public $editindex;
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

    //related subcatecory functions
    public function showColumn($column)
    {
        if ($column === 'Id') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function edititem($index, $id)
    {
        $this->editindex = $index;
        $record = Address::find($id);
        $this->adress = [
            $index . '.first_name' => $record->first_name,
            $index . '.last_name' => $record->last_name,
            $index . '.phone' => $record->phone,
            $index . '.email' => $record->email,
            $index . '.address1' => $record->address1,
            $index . '.address2' => $record->address2,
            $index . '.country' => $record->country,
            $index . '.county' => $record->county,
            $index . '.city' => $record->city,
            $index . '.zipcode' => $record->zipcode,
        ];
    }

    public function saveitem($index, $id)
    {
        $record = $this->adress[$index] ?? NULL;
        if (!is_null($record)) {
            $new = Address::find($id);
            if (array_key_exists('first_name', $record)) {
                $new->first_name = $record['first_name'];
            }
            if (array_key_exists('last_name', $record)) {
                $new->last_name = $record['last_name'];
            }
            if (array_key_exists('phone', $record)) {
                $new->phone = $record['phone'];
            }
            if (array_key_exists('email', $record)) {
                $new->email = $record['email'];
            }
            if (array_key_exists('address1', $record)) {
                $new->address1 = $record['address1'];
            }
            if (array_key_exists('address2', $record)) {
                $new->address2 = $record['address2'];
            }
            if (array_key_exists('country', $record)) {
                $new->country = $record['country'];
            }
            if (array_key_exists('county', $record)) {
                $new->county = $record['county'];
            }
            if (array_key_exists('city', $record)) {
                $new->city = $record['city'];
            }
            if (array_key_exists('zipcode', $record)) {
                $new->zipcode = $record['zipcode'];
            }
            $new->save();
            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        } else {
            session()->flash('notification', [
                'message' => 'Nothing was edited!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }
        $this->editindex = null;
        $this->adress = [];
    }

    public function canceledit()
    {
        $this->editindex = null;
        $this->adress = [];
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

    public function deleteSingleRecord()
    {
        $record = Address::findOrFail($this->idbeingremoved);
        $record->delete();
        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
        $this->single = false;
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
        $this->multiple = false;
        session()->flash('notification', [
            'message' => 'Records deleted successfully!',
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
    public function render()
    {
        return view('livewire.related-addresses', [
            'addresses' => $this->addresses,
        ]);
    }
    public function mount($account)
    {
        $this->accountId = $account->id;
        $this->account = $account;
        $this->selectedColumns = $this->columns;
    }
}