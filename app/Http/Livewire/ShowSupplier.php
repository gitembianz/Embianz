<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Currency;
use App\Models\Exchange;
use App\Models\Order_Supplier;
use Illuminate\Support\Facades\Auth;


class ShowSupplier extends Component
{
    public $itemId;
    public $edititem = null;
    public $delete = false;
    public $record;
    public $currencies;
    public $rate = [];
    public $search = '';

    public $totalPrice;

    public function render()
    {
        $exchanges = Exchange::search($this->search)
            ->where(function ($query) {
                $query->whereHas('base_currency', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
                $query->whereHas('quote_currency', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->get();
        return view('livewire.show-supplier', [
            'supplier' => $this->supplier,
            'exchanges' => $exchanges

        ]);
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->rate = [
            'allow' => false,
            'itemselected' => null,
            'idrel' => null,
        ];
    }
    public function allowselect()
    {
        $this->rate['allow'] = true;
    }
    public function selectitem($id)
    {
        $exchange = Exchange::with(['base_currency', 'quote_currency'])->find($id);
        $this->rate = [
            'itemselected' => $exchange->base_currency->name . '/' . $exchange->quote_currency->name . ' date - ' . $exchange->date . ' value - ' . $exchange->value,
            'idrel' => $id,
            'allow' => false,
        ];
        $this->search = '';
    }
    public function dennyselect()
    {
        $this->rate['allow'] = false;
    }
    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function deleteSingleRecord()
    {
        $id = $this->itemId;
        $record = Order_Supplier::findOrFail($id);
        foreach ($record->items as $item) {
            $item->delete();
        }
        $record->delete();
        $this->delete = false;
        return redirect()->route('suppliers')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function getSupplierQueryProperty()
    {
        $supplier = Order_Supplier::with('items')->find($this->itemId);
        return $supplier;
    }

    public function getSupplierProperty()
    {
        return $this->supplierQuery;
    }
    public function edititem()
    {
        $this->currencies = Currency::all();

        $this->record = [
            'name' => $this->supplier->name,
            'supplier_name' => $this->supplier->supplier_name,
            'date' => $this->supplier->date,
            'status' => $this->supplier->status,
            'currency' => $this->supplier->currency
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
        if (!$this->record) return;

        $rec = $this->record;


        $this->supplier->name = $rec['name'];
        $this->supplier->supplier_name = $rec['supplier_name'] ?? null;
        $this->supplier->currency = $rec['currency'];
        $this->supplier->date = $rec['date'];
        $this->supplier->status = $rec['status'];

        if ($rec['status'] != "draft" && !empty($this->supplier->items)) {
            foreach ($this->supplier->items as $item) {
                $product = $item->product;

                $interimQuantity = $product->orders_item()
                    ->whereHas('order', function ($q) {
                        $q->where('status_id', 31);
                    })->sum('quantity');

                $item->product_quantity_interim = $product->quantity + $interimQuantity;
                $item->product_quantity = $product->quantity;
                $item->save();
            }
        }

        if (isset($this->rate['idrel']) && $this->rate['idrel'] != null) {
            $this->supplier->exchange_id = $this->rate['idrel'];
        }

        $this->supplier->last_modified_by = Auth::user()->name ?? 'Unknown';
        $this->supplier->save();
        if ($this->supplier->currency == $this->supplier->exchange->base_currency->name) {
            $this->supplier->final_amount_quote_currency = $this->supplier->exchange->value * $this->supplier->final_amount;
        } elseif ($this->supplier->currency == $this->supplier->exchange->quote_currency->name) {
            $this->supplier->final_amount_quote_currency = 1 / $this->supplier->exchange->value * $this->supplier->final_amount;
        }
        $this->supplier->save();


        $this->emit('itemSaved');
        session()->flash('notification', [
            'message' => 'Record edited successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);

        $this->record = [];
        $this->edititem = null;
    }
}
