<?php

namespace App\Http\Livewire;

use App\Models\County;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class ShowCounty extends Component
{
    public $itemId;
    public $delete = false;
    public $record;
    public $edititem = null;

    public function render()
    {
        return view('livewire.show-county', [
            'county' => $this->county
        ]);
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
    }
    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function getCountyQueryProperty()
    {
        return County::find($this->itemId);
    }
    public function getCountyProperty()
    {
        return $this->countyQuery;
    }
    public function edititem()
    {
        $this->record = [
            'name' => $this->county->name,
            'status' => $this->county->status == 1 ? true : false,
            'iso_code' => $this->county->iso_code,
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
            $new = County::find($this->itemId);
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

            if (array_key_exists('status', $rec)) {
                $new->status = $rec['status'];
            }
            if (array_key_exists('iso_code', $rec)) {
                $new->iso_code = $rec['iso_code'];
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