<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CartQuantity extends Component
{
    public $cart;

    protected $listeners = [
        'cartUpdated' => 'update',
    ];

    public function mount($cart)
    {
        $this->cart = $cart;
    }

    public function update()
    {
        $this->mount($this->cart);
    }

    public function render()
    {
        return view('livewire.cart-quantity');
    }
}
