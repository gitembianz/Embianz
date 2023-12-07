<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;

class ProductWishlistButton extends Component
{
    public $wishlists;
    public $product;
    public $session_id;
    public $wishlist = [];

    protected $listeners = [
        'wishlistUpdated' => 'refreshComponent'
    ];


    public function mount($product)
    {
        $cookieId = isset($_COOKIE['sessionId']) && !empty($_COOKIE['sessionId'])
            ? $_COOKIE['sessionId']
            : null;

        // If $cookieId is null, use Session::getId()
        $this->session_id = $cookieId ?? Session::getId();
        $this->product = $product;
        $this->wishlists = $this->product->wishlists->where('session_id', $this->session_id);
    }
    public function refreshComponent()
    {
        $this->mount($this->product);
        $this->render();
    }
    public function addToWishlist($id)
    {
        if (!in_array($id, $this->wishlist)) {
            $this->wishlist[] = $id;
            $this->saveToSession();

            Wishlist::updateOrCreate(
                ['session_id' => $this->session_id, 'product_id' => $id]
            );
            $this->emit('wishlistUpdated');
        }
    }
    public function removeFromWishlist($id)
    {
        $this->wishlist = array_diff($this->wishlist, [$id]);
        $this->saveToSession();

        Wishlist::where('session_id', $this->session_id)
            ->where('product_id', $id)
            ->delete();
        $this->emit('wishlistUpdated');
    }
    private function saveToSession()
    {
        session([
            'wishlist' => $this->wishlist,
        ]);
    }
    public function toggleWishlist($id)
    {
        if (in_array($id, $this->wishlist)) {
            $this->removeFromWishlist($id);
        } else {
            $this->addToWishlist($id);
        }
    }
    public function render()
    {
        return view('livewire.product-wishlist-button');
    }
}
