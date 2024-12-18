<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use App\Models\Order_Item;
use Livewire\WithPagination;

class RelatedOrderItems extends Component
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
    public $showrelatedprod = false;
    public $orderId;
    public $col = false;
    public $all = false;
    public $idbeingremoved = null;
    public $columns = ['Id', 'Price', 'Quantity', 'VAT'];
    public $selectedColumns = [];
    public $order;
    public $rand = null;
    public $rind2 = null;

    public $single = false;
    public $multiple = false;
    public $additems = false;
    //add declaration
    public $productsAndValues = [];
    public $searchadd = '';
    public $showTable = false;
    public $loadAmount = 20;
    public $row = 1;

    public function saveitems()
    {
        foreach ($this->productsAndValues as  $array) {
            if (isset($array['product']['quantity']) && isset($array['product']['idrel'])) {
                Order_Item::create([
                    'order_id' => $this->orderId,
                    'product_id' => $array['product']['idrel'],
                    'price' => $array['price'],
                    'quantity' => $array['product']['quantity'],
                    'vat' => $array['vat']
                ]);
                $this->order->quantity_amount += $array['product']['quantity'];
                $this->order->sum_amount += ($array['price'] * $array['product']['quantity']);
                $this->order->save();
                $this->order->final_amount = $this->order->sum_amount + $this->order->delivery_price - $this->order->promotion_value - $this->order->voucher_value;
                $this->order->save();
            } else {
                session()->flash('notification', [
                    'message' => 'Please provide values',
                    'type' => 'warning',
                    'title' => 'Missing Values'
                ]);
                return;
            }
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
    public function selectitem($index, $id, $name, $price, $vat)
    {
        $this->productsAndValues[$index]['itemselected'] = $name;
        $this->productsAndValues[$index]['price'] = $price;
        $this->productsAndValues[$index]['vat'] = $vat;

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
            'price' => null,
            'vat' => null,
            'product' => ['name' => null, 'quantity' => 1]
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
                'price' => null,
                'vat' => null,
                'product' => ['name' => null, 'quantity' => 1]
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
            'price' => null,
            'vat' => null,
            'product' => ['name' => null, 'quantity' => 1]
        ];
        $this->row = 1;
        $this->showTable = false;
    }
    public function getProductsProperty()
    {
        $relatedIds = $this->order->orders->pluck('product_id')->toArray();

        // Start the query for products not in the relatedIds and not of type 'parent'
        $unrelatedQuery = Product::whereNotIn('id', $relatedIds)
            ->where('type', '!=', 'parent')
            ->whereHas('product_prices', function ($query) {
                $query->whereNotNull('value') // Ensure the price value is not null
                    ->where('value', '>', 0); // Ensure the price value is greater than 0
            });

        // Add the search filter if $this->searchadd is not empty
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
            })->get();


        return view('livewire.related-order-items', [
            'orderproducts' => $orderproducts,
            'products' => $this->products,

        ]);
    }
    public function addorderitems()
    {
        $this->additems = true;
    }
    public function mount(Order $order)
    {
        $this->orderId = $order->id;
        $this->order = $order;
        $this->selectedColumns = $this->columns;
        $this->productsAndValues[] = [
            'allow' => false,
            'itemselected' => null,
            'price' => null,
            'vat' => null,
            'product' => ['name' => null, 'quantity' => 1]
        ];
    }
    //function for related products
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
        $this->perPage += 10;
    }
    public function getOrderproductsProperty()
    {
        return $this->orderproductsQuery;
    }
    public function getOrderproductsQueryProperty()
    {
        return Order_Item::where('order_id', $this->orderId)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }
    public function deleteSingleRecord()
    {
        $item = Order_Item::findOrFail($this->idbeingremoved);
        $this->order->quantity_amount -= $item->quantity;
        $this->order->sum_amount -= $item->price;
        $this->order->save();
        $this->order->final_amount = $this->order->sum_amount + $this->order->delivery_price - $this->order->promotion_value - $this->order->voucher_value;
        if ($this->order->sum_amount == 0) {
            $this->order->final_amount = 0;
        }
        $this->order->save();
        $item->delete();
        $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
        $this->single = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
    public function increment($id)
    {
        $item = Order_Item::findOrFail($id);
        $item->quantity += 1;
        $item->save();
        $this->order->quantity_amount += 1;
        $this->order->sum_amount += $item->price;
        $this->order->save();
        $this->order->final_amount = $this->order->sum_amount + $this->order->delivery_price - $this->order->promotion_value - $this->order->voucher_value;
        $this->order->save();
    }
    public function decrement($id)
    {

        $item = Order_Item::findOrFail($id);
        if ($item->quantity > 1) {
            $item->quantity -= 1;
            $item->save();
            $this->order->quantity_amount -= 1;
            $this->order->sum_amount -= $item->price;
            $this->order->save();
            $this->order->final_amount = $this->order->sum_amount + $this->order->delivery_price - $this->order->promotion_value - $this->order->voucher_value;
            $this->order->save();
        } else {
            $this->idbeingremoved = $item->id;
            $this->deleteSingleRecord();
        }
    }
    public function deleteRecords()
    {
        $items = Order_Item::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $id = $item->id;
            $del = Order_Item::find($id);
            $this->order->quantity_amount -= $del->quantity;
            $this->order->sum_amount -= $del->price;
            $this->order->save();
            $this->order->final_amount = $this->order->sum_amount + $this->order->delivery_price - $this->order->promotion_value - $this->order->voucher_value;
            if ($this->order->sum_amount == 0) {
                $this->order->final_amount = 0;
            }
            $this->order->save();
            $del->delete();
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
}
