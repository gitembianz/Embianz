<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Status;
use Livewire\Component;
use App\Models\Order_Item;

class ShowOrder extends Component
{
    public $orderId;
    public $record = [];
    public $edititem = null;
    public $statuses;
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
        return Order::with('account', 'currency', 'status', 'cart')->find($this->orderId);
    }
    public function mount($orderId)
    {
        $this->orderId = $orderId;
    }
    public function canceledit()
    {
        $this->edititem = null;
        $this->record = [];
    }
    public function edititem()
    {
        $this->statuses = Status::where('type', 'order')->get();
        $this->record = [
            'status_id' => $this->order->status_id,
        ];
        $this->edititem = true;
    }
    public function saveitem()
    {
        $new_status = $this->record ?? NULL;
        if (!is_null($new_status)) {
            $order = Order::find($this->orderId);
            if (array_key_exists('status_id', $new_status)) {
                $order->status_id = $new_status['status_id'];
                $order->updated_at = now();
                $order->save();
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
