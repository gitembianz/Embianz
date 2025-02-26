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
    public $totalPrice;

    public function render()
    {
        return view('livewire.show-supplier', [
            'supplier' => $this->supplier
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
            'currency' => $this->supplier->currency,
            'quote_currency' => $this->supplier->quote_currency
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
        $this->supplier->quote_currency = $rec['quote_currency'];
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

        $c1 = optional(Currency::where('name', $rec['currency'])->first())->id;
        $c2 = optional(Currency::where('name', $rec['quote_currency'])->first())->id;
        $exchange_id = optional(Exchange::where('base_currency_id', $c1)
            ->where('quote_currency_id', $c2)
            ->first())->id;
        $this->supplier->last_modified_by = Auth::user()->name ?? 'Unknown';
        $this->supplier->exchange_id = $exchange_id;
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
