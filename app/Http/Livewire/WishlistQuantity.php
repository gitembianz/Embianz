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

    private function getSessionId()
    {
        if (array_key_exists('sessionId', $_COOKIE)) {
            return $_COOKIE['sessionId'];
        } else {
            $sessionId = session()->getId();
            setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);
            return $sessionId;
        }
    }

    public function mount()
    {
        $this->session_id = $this->getSessionId();
        $this->count = Wishlist::where('session_id', $this->session_id)->count();
    }
}
