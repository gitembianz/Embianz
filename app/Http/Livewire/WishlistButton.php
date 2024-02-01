<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;


class WishlistButton extends Component
{

    public $productId;
    public $is_in_wishlist;
    public $session_id;

    public $listeners = [];

    public function mount($productId)
    {
        $this->session_id = isset($_COOKIE['sessionId']) ? $_COOKIE['sessionId'] : session()->getId();

        $this->productId  = $productId;

        $this->is_in_wishlist = Wishlist::where('session_id', $this->session_id)
            ->where('product_id', $this->productId)
            ->first() ? true : false;
        $this->listeners = ["update-wish-" . $this->productId => "refreshComponent"];
    }

    public function refreshComponent()
    {
        $this->mount($this->productId);
    }
    public function addToWishlist($id)
    {
        Wishlist::updateOrCreate(
            ['session_id' => $this->session_id, 'product_id' => $id]
        );
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
