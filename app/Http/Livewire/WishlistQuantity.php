<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;

class WishlistQuantity extends Component
{
    public $count;
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
        $this->count = Wishlist::where('session_id', app('global_session_id'))->count();
    }
}
