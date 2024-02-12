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
    public function wishshow()
    {
        $this->showwis = true;
        $this->mount();
    }

    public function mount()
    {
        $this->session_id = $this->getSessionId();

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
        $this->emit('wishlistProductRemoved');
        $this->emit('update-wish-' . $productId);
        $this->mount();
    }
}