<?php

namespace App\Http\Livewire;

use App\Models\Country;
use Database\Seeders\CountrySeeder;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class Countriestable extends Component
{
    use WithPagination;
    public $loadAmount = 30;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $itemidbeingremoved = null;
    public $columns = ['id', 'name', 'iso_code', 'iso_code3', 'phone_code', 'currency', 'status', 'created_at', 'updated_at'];
    public $selectedColumns = [];
    public $rowindex = null;
    public $element = [];

    public function render()
    {
        return view('livewire.countriestable', [
            'countries' => $this->countries
        ]);
    }

    public function mount()
    {
        $this->selectedColumns = $this->columns;
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }


    public function loadMore()
    {
        $this->loadAmount += 10;
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
    public function getCountriesProperty()
    {
        return Country::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount);
    }
    public function edititem($index, $id)
    {
        $record = Country::find($id);
        $this->rowindex = $index;
        $this->element[$index]  = [
            'name' => $record->name,
            'iso_code' => $record->iso_code,
            'iso_code3' => $record->iso_code3,
            'phone_code' => $record->phone_code,
            'currency' => $record->currency,
            'status' => $record->status == 1 ? true : false,
        ];
    }
    public function saveitem($index, $id)
    {
        $record = $this->element[$index] ?? null;
        if (!$record) {
            session()->flash('notification', [
                'message' => 'Nothing was edited!',
                'type' => 'warning',
                'title' => 'Warning'
            ]);
            return;
        }
        $element = Country::find($id);
        $fillableFields = [
            'name',
            'iso_code',
            'iso_code3',
            'phone_code',
            'currency',
            'status'
        ];
        foreach ($fillableFields as $field) {
            if (array_key_exists($field, $record)) {
                $element->{$field} = $record[$field];
            }
        }
        $element->save();
        Cache::forget('active_countries');

        session()->flash('notification', [
            'message' => 'Record edited successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);

        $this->rowindex = null;
        $this->element = [];
    }
    public function cancelitem()
    {
        $this->rowindex = null;
        $this->element = [];
    }
    public function addCountriesIfNotExist()
    {
        $element = CountrySeeder::labels();
        foreach ($element as $elem) {
            $exists = DB::table('countries')
                ->where('name', $elem['name'])
                ->exists();

            if (!$exists) {
                DB::table('countries')->insert([
                    'name' => $elem['name'],
                    'iso_code' => $elem['iso_code'],
                    'iso_code3' => $elem['iso_code3'],
                    'phone_code' => $elem['phone_code'],
                    'currency' => $elem['currency'],
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        Cache::forget('active_countries');

        session()->flash('notification', [
            'message' => 'Countries update successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}