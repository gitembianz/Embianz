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
        $this->session_id = $this->getSessionId();

        $this->count = Wishlist::where('session_id', $this->session_id)->count();
    }
    private function getSessionId()
    {
        if (array_key_exists('sessionId', $_COOKIE)) {
            return $_COOKIE['sessionId'];
        } else {
            $sessionId = session()->getId();
            return $sessionId;
        }
    }
}
