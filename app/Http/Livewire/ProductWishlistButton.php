<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;

class ProductWishlistButton extends Component
{
    public $wishlists;
    public  $productId;
    public $is_in_wishlist;
    public $session_id;

    public function mount($productId)
    {
        $cookieId = isset($_COOKIE['sessionId']) && !empty($_COOKIE['sessionId'])
            ? $_COOKIE['sessionId']
            : null;
        $this->session_id = $cookieId ?? Session::getId();
        $this->productId  = $productId;

        // Use first() to execute the query and get a single result
        $wishlistItem = Wishlist::where('session_id', $this->session_id)
            ->where('product_id', $this->productId)
            ->first();

        // If the wishlist item exists, set $this->is_in_wishlist to true, otherwise false
        $this->is_in_wishlist = $wishlistItem ? true : false;
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
        return view('livewire.product-wishlist-button')->with('productId', $this->productId);
    }
}
