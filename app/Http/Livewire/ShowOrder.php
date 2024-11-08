<?php

namespace App\Http\Livewire;

use App\Models\Order;
use App\Models\Status;
use Livewire\Component;

class ShowOrder extends Component
{
    public $orderId;
    public $record = [];
    public $edititem = null;
    public $delete = false;
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
            'invoice_date' => $this->order->invoice_date,
            'invoice_number' => $this->order->invoice_number

        ];
        $this->edititem = true;
    }
    public function saveitem()
    {
        $new = $this->record ?? NULL;
        if (!is_null($new)) {
            $order = Order::find($this->orderId);
            $oldstatus = $order->status_id;
            $statusclose = Status::where('type', 'order')->where('name', 'canceled')->first()->id;
            if (array_key_exists('invoice_date', $new)) {
                $order->invoice_date = $new['invoice_date'];
            }
            if (array_key_exists('invoice_number', $new)) {
                $order->invoice_number = $new['invoice_number'];
            }
            if (array_key_exists('status_id', $new)) {
                $order->status_id = $new['status_id'];
                $order->updated_at = now();
                $order->save();
                if ($oldstatus != $new['status_id'] && $new['status_id'] == $statusclose) {
                    foreach ($order->orders as $orderitem) {
                        $orderitem->product->quantity += $orderitem->quantity;
                        $orderitem->product->save();
                    }
                } elseif ($oldstatus == $statusclose && $new['status_id'] != $statusclose) {
                    foreach ($order->orders as $orderitem) {
                        $orderitem->product->quantity -= $orderitem->quantity;
                        $orderitem->product->save();
                    }
                }
                $this->emit('itemSaved');
                session()->flash('notification', [
                    'message' => 'Record edited successfully!',
                    'type' => 'success',
                    'title' => 'Success'
                ]);
            }
            $order->save();
        }
        $this->record = [];
        $this->edititem = null;
    }
    public function confirmItemRemoval()
    {
        $this->delete = true;
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function deleteRecord()
    {
        $order = Order::findOrFail($this->orderId);
        foreach ($order->orders as $orderitem) {
            $orderitem->product->quantity += $orderitem->quantity;
            $orderitem->product->save();
            $orderitem->delete();
        }

        $order->delete();
        $this->delete = false;
        return redirect()->route('orders')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}
