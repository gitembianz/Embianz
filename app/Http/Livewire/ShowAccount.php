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
    protected $listeners = [
        'itemSaved' => 'mount'
    ];

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
        if ($this->account->type == 'individual') {
            $this->record = [
                'name' => $this->account->name,
                'type' => $this->account->type,
                'first_name' => $this->account->first_name,
                'last_name' => $this->account->last_name,
                'phone' => $this->account->phone,
                'email' => $this->account->email,
            ];
        } else {
            $this->record = [
                'name' => $this->account->name,
                'type' => $this->account->type,
                'first_name' => $this->account->first_name,
                'last_name' => $this->account->last_name,
                'phone' => $this->account->phone,
                'email' => $this->account->email,
                'company_name' => $this->account->company_name,
                'registration_code' => $this->account->registration_code,
                'registration_number' => $this->account->registration_number,
                'bank_name' => $this->account->bank_name,
                'account' => $this->account->account,
            ];
        }
        $this->edititem = true;
    }

    public function saveitem()
    {
        $rec = $this->record ?? NULL;
        if (!is_null($rec)) {
            $new = Account::find($this->accountId);
            if (array_key_exists('name', $rec)) {
                if (!empty($rec['name'])) {
                    $new->name = $rec['name'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide a value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('type', $rec)) {
                if (!empty($rec['type'])) {
                    if ($rec['type'] == 'individual' || $rec['type'] == 'juridic') {
                        $new->type = $rec['type'];
                    } else {
                        session()->flash('notification', [
                            'message' => 'Type must be individual or juridic!',
                            'type' => 'warning',
                            'title' => 'Missing Values'
                        ]);
                        return;
                    }
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide type value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('first_name', $rec)) {
                if (!empty($rec['first_name'])) {
                    $new->first_name = $rec['first_name'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide First Name value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('last_name', $rec)) {
                if (!empty($rec['last_name'])) {
                    $new->last_name = $rec['last_name'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Last Name value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('phone', $rec)) {
                if (!empty($rec['phone'])) {
                    $new->phone = $rec['phone'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Phone value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('email', $rec)) {
                if (!empty($rec['email'])) {
                    $new->email = $rec['email'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Email value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('company_name', $rec)) {
                if (!empty($rec['company_name'])) {
                    $new->company_name = $rec['company_name'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Company Name value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('registration_number', $rec)) {
                if (!empty($rec['registration_number'])) {
                    $new->registration_number = $rec['registration_number'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Registration Number value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('registration_code', $rec)) {
                if (!empty($rec['registration_code'])) {
                    $new->registration_code = $rec['registration_code'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Registration Code value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('bank_name', $rec)) {
                if (!empty($rec['bank_name'])) {
                    $new->bank_name = $rec['bank_name'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Bank Name value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('account', $rec)) {
                if (!empty($rec['account'])) {
                    $new->account = $rec['account'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide Bank Account value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            $new->updated_at = now();
            $new->save();
            $this->emit('itemSaved');
            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
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
