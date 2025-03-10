<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use App\Models\Exchange;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;



class Exchangestable extends Component
{
    use WithPagination;
    public $loadAmount = 30;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $idbeingremoved = null;
    public $columns = [];
    public $selectedColumns = [];
    public $rowindex = null;
    public $currencies;
    public $single = false;
    public $tableName;

    public $add = false;
    public $rowadd;
    public $base = [];
    public $quote = [];
    public $value = [];
    public $date = [];
    public $element = [];
    public $row = 1;

    public function render()
    {
        return view('livewire.exchangestable', [
            'exchanges' => $this->exchanges
        ]);
    }
    public function mount()
    {
        $this->rowadd = 1;
        $this->columns = Schema::getColumnListing($this->tableName);

        $this->selectedColumns = $this->columns;
        $this->currencies = Currency::all();
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
    public function getExchangesProperty()
    {
        return Exchange::search($this->search)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->loadAmount);
    }
    public function plus()
    {
        $this->rowadd++;
        $this->base[$this->rowadd] = null;
        $this->quote[$this->rowadd] = null;
        $this->value[$this->rowadd] = null;
        $this->date[$this->rowadd] = null;
    }
    public function clear($i)
    {
        array_splice($this->base, $i, 1);
        array_splice($this->quote, $i, 1);
        array_splice($this->value, $i, 1);
        array_splice($this->date, $i, 1);


        $this->rowadd--;

        if ($this->rowadd < 0) {
            $this->add = false;
            $this->rowadd = 0;
            $this->base = [];
            $this->quote = [];
            $this->value = [];
            $this->date = [];
        }
    }

    public function saveadd()
    {
        for ($i = 1; $i <= $this->rowadd; $i++) {
            $this->resetErrorBag();

            $this->validate([
                'base.*' => 'required',
                'quote.*' => 'required'
            ]);

            Exchange::create([
                'base_currency_id' => $this->base[$i],
                'quote_currency_id' => $this->quote[$i],
                'value' => $this->value[$i] ?? 1,
                'date' => $this->date[$i],
                'created_by' => Auth::user()->name,
                'last_modified_by' => Auth::user()->name,
            ]);
            array_splice($this->base, $i, 1);
            array_splice($this->quote, $i, 1);
            array_splice($this->value, $i, 1);
            array_splice($this->date, $i, 1);
        }
        session()->flash('notification', [
            'message' => 'Exchanges added successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->add = false;
        $this->rowadd = 0;
        $this->base = [];
        $this->quote = [];
        $this->value = [];
        $this->date = [];
    }
    public function edititem($index, $id)
    {
        $record = Exchange::find($id);
        $this->rowindex = $index;
        $this->element[$index]  = [
            'base_currency_id' => $record->base_currency_id,
            'quote_currency_id' => $record->quote_currency_id,
            'value' => $record->value,
            'date' => $record->date
        ];
    }
    public function cancelitem()
    {
        $this->rowindex = null;
        $this->element = [];
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
        $element = Exchange::find($id);
        $fillableFields = [
            'base_currency_id',
            'quote_currency_id',
            'value',
            'date',
            'last_modified_by'
        ];
        foreach ($fillableFields as $field) {
            if (array_key_exists($field, $record)) {
                $element->{$field} = $record[$field];
            }
        }
        $element->last_modified_by = Auth::user()->name;
        $element->save();
        session()->flash('notification', [
            'message' => 'Record edited successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);

        $this->rowindex = null;
        $this->element = [];
    }
    public function deleteSingleRecord()
    {
        $item = Exchange::findOrFail($this->idbeingremoved);
        foreach ($item->suppliers as $supplier) {
            $supplier->exchange_id = null;
            $supplier->final_amount_quote_currency = 0;
            $supplier->save();
        }
        $item->delete();
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
}
