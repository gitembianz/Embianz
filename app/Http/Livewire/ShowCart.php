<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Cart_Item;
use App\Models\Status;
use Livewire\Component;

class ShowCart extends Component
{
    public $cartId;
    public $record = [];
    public $edititem = null;
    public $statuses;
    public function render()
    {
        return view('livewire.show-cart', [
            'cart' => $this->cart
        ]);
    }
    public function getCartProperty()
    {
        return $this->cartQuery;
    }
    public function getCartQueryProperty()
    {
        return Cart::where('id', $this->cartId)->with('currency', 'status', 'order')->first();
    }
    public function mount($cartId)
    {
        $this->cartId = $cartId;
    }
    public function cancelcart()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function edititem()
    {
        $this->statuses = Status::where('type', 'cart')->get();
        $this->record = [
            'status_id' => $this->cart->status_id,
        ];
        $this->edititem = true;
    }
    public function savecart()
    {
        $new_status = $this->record ?? NULL;
        if (!is_null($new_status)) {
            $cart = Cart::find($this->cartId);
            if (array_key_exists('status_id', $new_status)) {
                $cart->status_id = $new_status['status_id'];
                $cart->updated_at = now();
                $cart->save();
                $this->emit('itemSaved');
                session()->flash('notification', [
                    'message' => 'Record edited successfully!',
                    'type' => 'success',
                    'title' => 'Success'
                ]);
            }
        }
        $this->record = [];
        $this->edititem = null;
    }
    public function confirmItemRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function deleteRecord()
    {
        $item = Cart::findOrFail($this->cartId);
        $cartitems = Cart_Item::where('cart_id', $this->cartId)->get();

        if ($cartitems != NULL) {
            foreach ($cartitems as $cartitem) {
                $cartitem->delete();
            }
        }
        $item->delete();
        return redirect()->route('carts')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
