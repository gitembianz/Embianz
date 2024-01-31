<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;

class ProductWishlistButton extends Component
{
    public $productId;
    public $is_in_wishlist;
    public $session_id;

    public $listeners = [];

    public function mount($productId, $is_in_wishlist)
    {
        $cookieId = isset($_COOKIE['sessionId']) && !empty($_COOKIE['sessionId'])
            ? $_COOKIE['sessionId']
            : null;
        $this->session_id = $cookieId ?? Session::getId();
        $this->productId  = $productId;
        $this->is_in_wishlist = $is_in_wishlist;
        $this->listeners = ["update-wish-" . $this->productId => "refreshComponent"];
    }

    public function refreshComponent()
    {
        $this->is_in_wishlist = !$this->is_in_wishlist;
        $this->mount($this->productId, $this->is_in_wishlist);
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
        return view('livewire.product-wishlist-button');
    }
}
