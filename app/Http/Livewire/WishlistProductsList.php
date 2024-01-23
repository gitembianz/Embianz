<?php

namespace App\Http\Livewire;

use App\Models\Wishlist;
use Livewire\Component;

class WishlistProductsList extends Component
{
    public $showwis = false;
    public $items;
    public $session_id;
    protected $listeners = [
        'showwis' => 'wishshow'
    ];

    public function render()
    {
        return view('livewire.wishlist-products-list');
    }

    public function wishshow()
    {
        $this->showwis = true;
        $this->mount();
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

        $this->items = Wishlist::where('session_id', $this->session_id)->with([
            'product.media' => function ($query) {
                $query->where('type', 'min');
            },
            'product'
        ])->get();
    }

    public function removeFromWishlist($productId)
    {
        Wishlist::where('session_id', $this->session_id)
            ->where('product_id', $productId)
            ->delete();
        $this->emit('wishlistUpdated');
        $this->mount();
    }
}
