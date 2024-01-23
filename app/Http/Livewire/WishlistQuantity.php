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
        if (array_key_exists('sessionId', $_COOKIE)) {
            $this->session_id = $_COOKIE['sessionId'];
        } else {
            // If not present, generate a new sessionId
            $sessionId = session()->getId();

            // Set the new sessionId in the cookie
            setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);

            $this->session_id = $sessionId;
        }
        $this->count = Wishlist::where('session_id', $this->session_id)->count();
    }
}
