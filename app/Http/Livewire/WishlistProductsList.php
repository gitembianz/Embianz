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
        $this->session_id = isset($_COOKIE['sessionId']) ? $_COOKIE['sessionId'] : session()->getId();
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
