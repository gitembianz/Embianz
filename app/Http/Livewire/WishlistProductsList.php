<?php

namespace App\Http\Livewire;

use App\Models\Wishlist;
use Livewire\Component;

class WishlistProductsList extends Component
{
    public $showwis = false;
    public $session_id;

    protected $listeners = [
        'showwis' => 'wishshow'
    ];

    public function render()
    {
        return view('livewire.wishlist-products-list', ['items' => $this->items]);
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
    }

    public function mount()
    {
        $this->session_id = $this->getSessionId();
    }

    public function getItemsProperty()
    {
        return Wishlist::select('id', 'product_id')
            ->where('session_id', $this->session_id)
            ->with([
                'product' => function ($query) {
                    $query->select('id', 'name', 'seo_id')->with(['media' => function ($query) {
                        $query->select('name', 'path')->where('type', 'min');
                    }]);
                }
            ])->get() ?? collect();
    }

    public function removeFromWishlist($productId)
    {
        Wishlist::where('session_id', $this->session_id)->where('product_id', $productId)->delete();
        $this->emit('wishlistProductRemoved');
        $this->emit('update-wish-' . $productId);
    }
}