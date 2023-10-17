<?php

namespace App\Http\Livewire;

use App\Models\Account;
use App\Models\Address;
use Livewire\Component;

class ShowAccount extends Component
{
    public $accountId;
    public $record = [];
    public $edititem = null;

    public function render()
    {
        return view('livewire.show-account', [
            'account' => $this->account
        ]);
    }


    public function getAccountProperty()
    {
        return $this->accountQuery;
    }
    public function getAccountQueryProperty()
    {
        return Account::find($this->accountId);
    }
    public function mount($accountId)
    {
        $this->accountId = $accountId;
    }
    public function canceledit()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function edititem()
    {
        $this->record = [
            // 'status' => $this->cart->status,
        ];
        $this->edititem = true;
    }
    public function saveitem()
    {
        $new_record = $this->record ?? NULL;
        if (!is_null($new_record)) {
            $item = Account::find($this->accountId);
            if (array_key_exists('status', $new_record)) {
                $item->status = $new_record['status'];
                $item->updated_at = now();
                $item->save();
                $this->emit('itemSaved');
            }
        }
        $this->record = [];
        $this->edititem = null;
    }
    public function confirmItemRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function deleteRecord()
    {
        $item = Account::findOrFail($this->accountId);
        $addresses = Address::where('account_id', $this->accountId)->get();

        if ($addresses != NULL) {
            foreach ($addresses as $address) {
                $address->delete();
            }
        }
        $item->delete();
        return redirect()->route('accounts')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
