<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;

class ProductWishlistButton extends Component
{
    public $productId;
    public $is_in_wishlist;

    public $listeners = [];

    public function mount($productId, $is_in_wishlist)
    {
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
            ['session_id' => app('global_session_id'), 'product_id' => $id]
        );
        $this->emit('wishlistUpdated');
        $this->refreshComponent();
    }

    public function removeFromWishlist($id)
    {
        Wishlist::where('session_id', app('global_session_id'))
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
