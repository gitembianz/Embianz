<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Order_Supplier;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Order_Supplier_Item;
use Illuminate\Support\Facades\Auth;


class RelatedOrderSupplier extends Component
{
    use WithPagination;
    public $search = '';
    public $orderBy = 'id';
    public $orderAsc = true;
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $showrelated = false;
    public $supplierId;
    public $col = false;
    public $all = false;
    public $idbeingremoved = null;
    public $columns = ['Id', 'Product', 'Total Quantity', 'Product Quantity Interim', 'Product Quantity', 'Quantity', 'Quantity Received', 'Price', 'VAT', 'Subtotal', 'Created by', 'Updated by', 'Created At', 'Updated At'];
    public $selectedColumns = [];
    public $supplier;
    public $rand = null;
    public $rind2 = null;
    public $order_item = [];

    public $single = false;
    public $multiple = false;
    public $additems = false;
    //add declaration
    public $productsAndValues = [];
    public $searchadd = '';
    public $showTable = false;
    public $loadAmount = 20;
    public $row = 1;
    public $editindex = null;

    public function edititem($index, $id)
    {
        $this->editindex = $index;
        $this->row = $index;
        $record = Order_Supplier_Item::find($id);
        $this->order_item[$index] = [
            'quantity_received' => $record->quantity_received,
            'quantity' => $record->quantity,
            'price' => $record->price,
            'vat' => $record->vat
        ];
    }
    public function canceledit()
    {
        $this->editindex = null;
        $this->order_item = [];
    }
    public function saveitem($index, $id)
    {
        $record = $this->order_item[$index] ?? null;

        if (is_null($record)) {
            session()->flash('notification', [
                'message' => 'Nothing was edited!',
                'type' => 'warning',
                'title' => 'Warning'
            ]);
            return;
        }

        $orderItem = Order_Supplier_Item::find($id);
        if (!$orderItem) {
            session()->flash('notification', [
                'message' => 'Order item not found!',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        $newQuantity = $record['quantity'] ?? null;
        $newQuantityReceived = $record['quantity_received'] ?? null;

        if (!is_null($newQuantity)) {
            $orderItem->quantity = $newQuantity;
        }
        if (!is_null($newQuantityReceived)) {
            $orderItem->quantity_received = $newQuantityReceived;
        }

        if ($orderItem->order->status != "closed") {
            $product = $orderItem->product;

            if (!is_null($newQuantityReceived)) {
                $product->quantity += ($newQuantityReceived - $orderItem->getOriginal('quantity_received'));
            }



            $product->save();
        }
        $orderItem->price = $record['price'];
        $orderItem->vat = $record['vat'];



        $orderItem->save();
        $orderItem->subtotal = ($orderItem->price + $orderItem->price * $orderItem->vat / 100) * $orderItem->quantity;
        $orderItem->save();

        if ($this->supplier && $this->supplier->items->isNotEmpty()) {
            $pu = 0;
            $total = 0;

            foreach ($this->supplier->items as $supplierItem) {
                $pu += $supplierItem->price;
                $total += $supplierItem->price + ($supplierItem->price * $supplierItem->vat / 100);
            }

            $vattotal = $total - $pu;
            $this->supplier->sum_amount = $pu;
            $this->supplier->vat_sum_amount = $vattotal;
            $this->supplier->final_amount = $total;
            $this->supplier->save();
        }

        session()->flash('notification', [
            'message' => 'Record edited successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);

        $this->editindex = null;
        $this->order_item = [];
    }


    public function saveitems()
    {
        foreach ($this->productsAndValues as $index =>  $array) {
            if (isset($array['product']['quantity']) && isset($array['product']['idrel'])) {
                $orderitem = Order_Supplier_Item::create([
                    'order__supplier_id' => $this->supplierId,
                    'product_id' => $array['product']['idrel'],
                    'quantity' => $array['product']['quantity'],
                    'price' => $array['product']['price'],
                    'vat' => $array['product']['vat'],
                    'subtotal' => $array['product']['quantity'] * ($array['product']['price'] + $array['product']['price'] *  $array['product']['vat'] / 100),
                    'created_by' => Auth::user()->name,
                    'last_modified_by' => Auth::user()->name
                ]);
                unset($this->productsAndValues[$index]);

                $this->productsAndValues = array_values($this->productsAndValues);
            } else {
                session()->flash('notification', [
                    'message' => 'Please provide values',
                    'type' => 'warning',
                    'title' => 'Missing Values'
                ]);
                return;
            }
        }

        if ($this->supplier && $this->supplier->items->isNotEmpty()) {
            $pu = 0;
            $total = 0;

            foreach ($this->supplier->items as $supplierItem) {
                $pu += $supplierItem->price;
                $total += $supplierItem->price + ($supplierItem->price * $supplierItem->vat / 100);
            }

            $vattotal = $total - $pu;
            $this->supplier->sum_amount = $pu;
            $this->supplier->vat_sum_amount = $vattotal;
            $this->supplier->final_amount = $total;
            $this->supplier->save();
        }


        $this->productsAndValues = [];
        $this->row = 1;
        $this->additems = false;

        session()->flash('notification', [
            'message' => 'Record related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }


    public function dennyselect($index)
    {
        $this->productsAndValues[$index]['allow'] = false;
        $this->searchadd = '';
    }
    public function selectitem($index, $id, $name)
    {
        $this->productsAndValues[$index]['itemselected'] = $name;
        $this->productsAndValues[$index]['product']['idrel'] = $id;
        $this->productsAndValues[$index]['allow'] = false;
        $this->searchadd = '';
    }
    public function plus()
    {
        $this->row++;
        $this->productsAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'product' => ['name' => null, 'quantity' => 1, 'price' => 0, 'vat' => 19]
        ];
    }
    public function clear($index)
    {
        unset($this->productsAndValues[$index]);

        $this->productsAndValues = array_values($this->productsAndValues);

        $this->row--;
        if ($this->row < 1) {
            $this->showTable = false;
            $this->productsAndValues[] = [
                'allow' => false,
                'itemselected' => null,
                'product' => ['name' => null, 'quantity' => 1, 'price' => 0, 'vat' => 19]
            ];
            $this->additems = false;
            $this->row = 1;
        }
    }
    public function allowselect($index)
    {
        foreach ($this->productsAndValues as &$item) {
            $item['allow'] = false;
        }
        $this->productsAndValues[$index]['allow'] = true;
        $this->searchadd = $this->productsAndValues[$index]['itemselected'];
    }
    public function closemodal()
    {
        $this->productsAndValues = [];
        $this->productsAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'product' => ['name' => null, 'quantity' => 1, 'price' => 0, 'vat' => 19]
        ];
        $this->row = 1;
        $this->additems = false;
    }
    public function getProductsProperty()
    {
        $relatedIds = $this->orderproducts->pluck('product_id')->toArray();

        $unrelatedQuery = Product::whereNotIn('id', $relatedIds)
            ->where('type', '!=', 'parent');

        if (!empty($this->searchadd)) {
            $unrelatedQuery->where('name', 'like', '%' . $this->searchadd . '%');
        }

        return $unrelatedQuery->get();
    }

    public function expandRow($index)
    {
        if ($this->rand  === null) {
            $this->rand = $index;
        } elseif ($this->rand != $index) {
            $this->rand = $index;
        } else {
            $this->rand = null;
        }
    }
    public function expandRow2($index)
    {
        if ($this->rind2  === null) {
            $this->rind2 = $index;
        } elseif ($this->rind2 != $index) {
            $this->rind2 = $index;
        } else {
            $this->rind2 = null;
        }
    }


    public function render()
    {
        $orderproducts = $this->orderproducts
            ->where(function ($query) {
                $query->whereHas('product', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->paginate($this->loadAmount);


        return view('livewire.related-order-supplier', [
            'orderproducts' => $orderproducts,
            'products' => $this->products,

        ]);
    }
    public function addorderitems()
    {
        $this->additems = true;
    }
    public function mount($supplierId)
    {
        $this->supplierId = $supplierId;
        $this->supplier = Order_Supplier::find($supplierId);
        $this->selectedColumns = $this->columns;
        $this->productsAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'price' => null,
            'vat' => null,
            'product' => ['name' => null, 'quantity' => 1, 'price' => 0, 'vat' => 19]
        ];
    }
    public function showColumn($column)
    {
        if ($column === 'Product') {
            return true;
        }
        return in_array($column, $this->selectedColumns);
    }
    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->checked = $this->orderproducts->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->orderproductsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function load()
    {
        $this->loadAmount += 10;
    }
    public function getOrderproductsProperty()
    {
        return $this->orderproductsQuery;
    }
    public function getOrderproductsQueryProperty()
    {
        return Order_Supplier_Item::with([
            'product' => function ($query) {
                $query->withCount([
                    'orders_item as interim_quantity' => function ($query) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status_id', 31);
                        })->select(DB::raw('sum(quantity)'));
                    },
                    'order_suppliers as total_quantity' => function ($query) {
                        $query->whereHas('order', function ($q) {
                            $q->where('status', 'draft'); // For 'total_quantity'
                        })->select(DB::raw('SUM(quantity)'));
                    }
                ]);
            }
        ])->where('order__supplier_id', $this->supplierId)
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
    public function deleteSingleRecord()
    {
        if (!$this->idbeingremoved) {
            session()->flash('notification', [
                'message' => 'No record selected for deletion!',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        // Find the item
        $item = Order_Supplier_Item::find($this->idbeingremoved);

        if (!$item) {
            session()->flash('notification', [
                'message' => 'Record not found!',
                'type' => 'error',
                'title' => 'Error'
            ]);
            return;
        }

        if ($item->product) {
            $item->product->quantity = max(0, $item->product->quantity - $item->quantity_received);
            $item->product->save();
        }

        $item->delete();

        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
        $this->single = false;

        if ($this->supplier && $this->supplier->items->isNotEmpty()) {
            $pu = 0;
            $total = 0;

            foreach ($this->supplier->items as $supplierItem) {
                $pu += $supplierItem->price;
                $total += $supplierItem->price + ($supplierItem->price * $supplierItem->vat / 100);
            }

            $vattotal = $total - $pu;
            $this->supplier->sum_amount = $pu;
            $this->supplier->vat_sum_amount = $vattotal;
            $this->supplier->final_amount = $total;
            $this->supplier->save();
        }

        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }

    public function deleteRecords()
    {
        $items = Order_Supplier_Item::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $id = $item->id;
            $del = Order_Supplier_Item::find($id);
            $del->product->quantity -= $del->quantity_received;
            $del->product->save();
            $del->delete();
        }

        if ($this->supplier && $this->supplier->items->isNotEmpty()) {
            $pu = 0;
            $total = 0;

            foreach ($this->supplier->items as $supplierItem) {
                $pu += $supplierItem->price;
                $total += $supplierItem->price + ($supplierItem->price * $supplierItem->vat / 100);
            }

            $vattotal = $total - $pu;
            $this->supplier->sum_amount = $pu;
            $this->supplier->vat_sum_amount = $vattotal;
            $this->supplier->final_amount = $total;
            $this->supplier->save();
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
