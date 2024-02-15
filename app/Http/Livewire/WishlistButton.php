<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;


class WishlistButton extends Component
{

    public $productId;
    public $is_in_wishlist;
    public $session_id;

    public $listeners = [];

    public function mount($productId)
    {
        $this->productId  = $productId;
        $this->session_id = $this->getSessionId();

        $this->is_in_wishlist = Wishlist::select('id')->where('session_id', $this->session_id)
            ->where('product_id', $this->productId)
            ->first() ? true : false;
        $this->listeners = ["update-wish-" . $this->productId => "refreshComponent"];
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

    public function refreshComponent()
    {
        $this->mount($this->productId);
    }

    public function addToWishlist($id)
    {
        Wishlist::updateOrCreate(['session_id' => $this->session_id, 'product_id' => $id]);
        $this->emit('wishlistUpdated');
        $this->refreshComponent();
    }
    public function removeFromWishlist($id)
    {
        Wishlist::where('session_id', $this->session_id)
            ->where('product_id', $id)
            ->delete();
        $this->emit('wishlistUpdated');
        $this->refreshComponent();
    }
    public function render()
    {
        return view('livewire.wishlist-button');
    }
}