<?php

namespace App\Http\Livewire;

use App\Models\Country;
use Livewire\Component;
use App\Models\Currency;
use Illuminate\Support\Facades\Cache;


class ShowCountry extends Component
{
    public $itemId;
    public $edititem = null;
    public $delete = false;
    public $record;
    public $currencies;
    public function render()
    {
        return view('livewire.show-country', [
            'country' => $this->country
        ]);
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->currencies = Currency::all();
    }
    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function getCountryQueryProperty()
    {
        return Country::find($this->itemId);
    }
    public function getCountryProperty()
    {
        return $this->countryQuery;
    }
    public function edititem()
    {
        $this->record = [
            'name' => $this->country->name,
            'currency' => $this->country->currency,
            'status' => $this->country->status == 1 ? true : false,
            'iso_code' => $this->country->iso_code,
            'iso_code3' => $this->country->iso_code3,
            'phone_code' => $this->country->phone_code,


        ];
        $this->edititem = true;
    }
    public function cancelitem()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function saveitem()
    {
        $rec = $this->record ?? NULL;
        if (!is_null($rec)) {
            $new = Country::find($this->itemId);
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
            if (array_key_exists('currency', $rec)) {
                if (!empty($rec['currency'])) {
                    $new->currency = $rec['currency'];
                } else {
                    session()->flash('notification', [
                        'message' => 'Please provide a value!',
                        'type' => 'warning',
                        'title' => 'Missing Values'
                    ]);
                    return;
                }
            }
            if (array_key_exists('status', $rec)) {
                $new->status = $rec['status'];
            }
            if (array_key_exists('iso_code', $rec)) {
                $new->iso_code = $rec['iso_code'];
            }
            if (array_key_exists('iso_code3', $rec)) {
                $new->iso_code3 = $rec['iso_code3'];
            }
            if (array_key_exists('phone_code', $rec)) {
                $new->phone_code = $rec['phone_code'];
            }
            $new->save();
            Cache::forget('active_countries');

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
}
