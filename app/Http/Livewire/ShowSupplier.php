<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Currency;
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
    public function getSupplierQueryProperty()
    {
        $supplier = Order_Supplier::with('items')->find($this->itemId);

        if ($supplier) {
            $this->totalPrice = $supplier->items->sum(function ($item) {
                return (int) $item->price;
            }) ?? 0;
        }

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
        $rec = $this->record ?? NULL;
        if (!is_null($rec)) {
            if (array_key_exists('name', $rec)) {
                if (!empty($rec['name'])) {
                    $this->supplier->name = $rec['name'];
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
                $this->supplier->currency = $rec['currency'];
            }
            if (array_key_exists('date', $rec)) {
                if (!empty($rec['date'])) {
                    $this->supplier->date = $rec['date'];
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
                $this->supplier->status = $rec['status'];
                if ($rec['status'] != "draft") {
                    foreach ($this->supplier->items as $item) {
                        $product = $item->product;

                        $interimQuantity = $product->orders_item()
                            ->whereHas('order', function ($q) {
                                $q->where('status_id', 31);
                            })->sum('quantity');

                        $item->product_quantity_interim = $item->product->quantity + $interimQuantity;
                        $item->product_quantity = $item->product->quantity;
                        $item->save();
                    }
                }
            }
            $this->supplier->last_modified_by = Auth::user()->name;
            $this->supplier->save();
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