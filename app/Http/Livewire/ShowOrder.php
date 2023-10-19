<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_Item;
use Livewire\Component;

class ShowOrder extends Component
{
    public $orderId;
    public $record = [];
    public $edititem = null;
    public $cart;
    public function render()
    {
        return view('livewire.show-order', [
            'order' => $this->order
        ]);
    }
    public function getOrderProperty()
    {
        return $this->orderQuery;
    }
    public function getOrderQueryProperty()
    {
        return Order::find($this->orderId);
    }
    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->cart = Cart::where('order_id', $this->orderId)->get('name');
    }
    public function canceledit()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function edititem()
    {
        $this->record = [
            // 'status' => $this->cart->status,
        ];
        $this->edititem = true;
    }
    public function confirmItemRemoval()
    {
        $this->dispatchBrowserEvent('show-delete-modal');
    }
    public function deleteRecord()
    {
        $item = Order::findOrFail($this->orderId);
        $orderitems = Order_Item::where('order_id', $this->orderId)->get();

        if ($orderitems != NULL) {
            foreach ($orderitems as $orderitem) {
                $orderitem->delete();
            }
        }
        $item->delete();
        return redirect()->route('orders')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
