<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Cart_Item;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedCartItems extends Component
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
    public $cartId;
    public $idbeingremoved = null;
    public $columns = ['Id', 'Price', 'Quantity', 'VAT'];
    public $selectedColumns = [];
    public $cart;
    public $row = null;
    public $single = false;
    public $multiple = false;


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
        $cartproducts = $this->cartproducts
            ->where(function ($query) {
                $query->whereHas('product', function ($subQuery) {
                    $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
                });
            })->get();


        return view('livewire.related-cart-items', [
            'cartproducts' => $cartproducts,
        ]);
    }
    public function mount(Cart $cart)
    {
        $this->cartId = $cart->id;
        $this->cart = $cart;
        $this->selectedColumns = $this->columns;
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
            $this->checked = $this->cartproducts->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
        $this->checked = $this->cartproductsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
    }
    public function load()
    {
        $this->perPage += 10;
    }
    public function getCartproductsProperty()
    {
        return $this->cartproductsQuery;
    }
    public function getCartproductsQueryProperty()
    {
        return Cart_Item::where('cart_id', $this->cartId)
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
    }

    public function deleteSingleRecord()
    {
        $id = $this->idbeingremoved;
        $item = Cart_Item::findOrFail($id);
        $this->cart->quantity_amount -= $item->quantity;
        $this->cart->save();
        $item->delete();
        $this->checked = array_diff($this->checked, [$id]);
        $this->single = false;
        session()->flash('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->emit('cartUpdated');
    }
    public function deleteRecords()
    {
        $items = Cart_Item::whereKey($this->checked)->get();
        foreach ($items as $item) {
            $id = $item->id;
            $del = Cart_Item::find($id);
            $this->cart->quantity_amount -= $del->quantity;
            $this->cart->save();
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
        $this->emit('cartUpdated');
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
