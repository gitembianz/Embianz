<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;

class WishlistQuantity extends Component
{
    public $count;
    public $session_id;

    protected $listeners = [
        'wishlistUpdated' => 'mount',
        'wishlistProductRemoved' => 'mount'
    ];

    public function render()
    {
        return view('livewire.wishlist-quantity');
    }

    public function mount()
    {
        $this->session_id = request()->cookie('sessionId') ?? session()->getId();


        $this->count = Wishlist::where('session_id', $this->session_id)->count();
    }
}
