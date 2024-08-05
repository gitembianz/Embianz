<?php

namespace App\Http\Livewire;

use App\Models\TextLabel;
use Database\Seeders\TextLabelSeeder;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;



class Labelstable extends Component
{
    public function render()
    {
        return view('livewire.labelstable', [
            'labels' => $this->labels
        ]);
    }
    use WithPagination;
    public $loadAmount = 20;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $itemidbeingremoved = null;
    public $columns = ['Id', 'Value', 'Description', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $rowindex = null;
    public $element = [];
    public $row = null;

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


    public function mount()
    {
        $this->selectedColumns = $this->columns;
    }
    public function showColumn($column)
    {
        if ($column === 'Parameter') {
            return true;
        }
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

    public function getLabelsProperty()
    {
        return TextLabel::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount);
    }


    public function edititem($index, $id)
    {
        $record = TextLabel::find($id);
        $this->rowindex = $index;
        $this->element = [
            $index . '.value' => $record->value,
            $index . '.description' => $record->description,
        ];
    }
    public function saveitem($index, $id)
    {
        $update = $this->element[$index] ?? NULL;
        if (!is_null($update)) {
            $item = TextLabel::find($id);

            if (array_key_exists('value', $update)) {
                $item->value = $update['value'];
            }
            if (array_key_exists('description', $update)) {
                $item->description = $update['description'];
            }
            $item->save();


            Cache::forget('label_variables');
            session()->flash('notification', [
                'message' => 'Record edited successfully!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        } else {
            session()->flash('notification', [
                'message' => 'Nothing chnaged!',
                'type' => 'success',
                'title' => 'Success'
            ]);
        }
        $this->element = [];
        $this->rowindex = null;
    }
    public function cancelitem()
    {
        $this->rowindex = null;
        $this->element = [];
    }


    public function addLabelsIfNotExist()
    {
        $element = TextLabelSeeder::labels();
        foreach ($element as $elem) {
            $exists = DB::table('text_labels')
                ->where('parameter', $elem['parameter'])
                ->exists();

            if (!$exists) {
                DB::table('text_labels')->insert([
                    'parameter' => $elem['parameter'],
                    'value' => $elem['value'],
                    'description' => $elem['description'],
                    'createdby' => 'admin',
                    'lastmodifiedby' => 'admin',
                    'created_at' => $elem['created_at'],
                    'updated_at' => $elem['updated_at']
                ]);
            }
        }
        Cache::forget('label_variables');
        session()->flash('notification', [
            'message' => 'Labels update successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
