<?php

namespace App\Http\Livewire;

use App\Models\Country;
use App\Models\County;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;


class RelatedCounty extends Component
{
    use WithPagination;
    //related delclaration/
    public $perPage = 10;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $showrelated = false;
    public $countryId;
    public $idbeingremoved = null;
    public $columns = ['Id', 'Name', 'Iso code', 'Status', 'Created at', 'Updated at'];
    public $selectedColumns = [];
    public $row = null;
    public $single = false;
    public $multiple = false;
    public $county;
    public $rand = 1;
    public $add = false;
    public $Values = [];
    public $editindex;
    public $item = [];


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
        return view('livewire.related-county', [
            'counties' => $this->counties,
        ]);
    }
    public function mount($countryId)
    {
        $this->countryId = $countryId;
        $this->selectedColumns = $this->columns;
        $this->Values[] = [
            'county' => ['name' => null, 'iso_code' => null,  'active' => false],
        ];
    }
    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->counties->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        return in_array($id, $this->checked);
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
        $this->checked = $this->countiesQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function load()
    {
        $this->perPage += 10;
    }
    public function getCountiesProperty()
    {
        return $this->countiesQuery->paginate($this->perPage);
    }
    public function getCountiesQueryProperty()
    {
        return County::where('country_id', $this->countryId)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
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

    //function for add new variant
    public function addrelated()
    {

        $this->add = true;
        $this->showrelated = true;
    }

    public function closemodal()
    {
        $this->Values = [];
        $this->Values = [
            'county' => ['name' => null, 'iso_code' => null,  'active' => false],
        ];
        $this->rand = 1;
        $this->add = false;
    }
    public function plus()
    {
        $this->rand++;
        $this->Values[] = [
            'county' => ['name' => null, 'iso_code' => null,  'active' => false],
        ];
    }
    public function clear($index)
    {
        unset($this->Values[$index]);

        $this->Values = array_values($this->Values);

        $this->rand--;
        if ($this->rand < 1) {
            $this->add = false;
            $this->Values = [
                'county' => ['name' => null, 'iso_code' => null,  'active' => false],
            ];
            $this->rand = 1;
        }
    }
    public function saveitems()
    {
        foreach ($this->Values as $index =>  $array) {
            if (isset($array['county']['name']) && isset($array['county']['iso_code'])) {

                County::create([
                    'country_id' => $this->countryId,
                    'name' => $array['county']['name'],
                    'iso_code' => $array['county']['iso_code'],
                    'status' => $array['county']['active'],
                ]);
            } else {
                session()->flash('notification', [
                    'message' => 'Please provide values',
                    'type' => 'warning',
                    'title' => 'Missing Values'
                ]);
                return;
            }
            unset($this->Values[$index]);

            $this->Values = array_values($this->Values);
        }

        $this->Values = [];
        $this->rand = 1;
        $this->add = false;
        session()->flash('notification', [
            'message' => 'Record related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->mount($this->county);
    }
    public function edititem($index, $id)
    {
        $this->editindex = $index;
        $this->row = $index;
        $record = County::find($id);
        $this->item[$index] = [
            'name' => $record->name,
            'iso_code' => $record->iso_code,
            'active' => $record->status == 1 ? true : false,
        ];
    }
    public function canceledit()
    {
        $this->editindex = null;
        $this->item = [];
    }
    public function saveitem($index, $id)
    {
        $record = $this->item[$index] ?? null;
        if (!is_null($record)) {
            $new = County::find($id);
            if (array_key_exists('name', $record)) {
                $new->name = $record['name'];
            }
            if (array_key_exists('iso_code', $record)) {
                $new->iso_code = $record['iso_code'];
            }
            if (array_key_exists('active', $record)) {
                $new->status = $record['active'];
            }

            $new->save();

            Cache::forget('active_countries');
            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        } else {
            session()->flash('notification', [
                'message' => 'Nothing was edited!',
                'type' => 'warning',
                'title' => 'Warning'
            ]);
        }
        $this->editindex = null;
        $this->item = [];
    }
    public function deleteSingleRecord()
    {
        $item = County::findOrFail($this->idbeingremoved);
        $item->delete();
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
        $items = County::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $item->delete();
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
}
