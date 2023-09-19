<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;

class StoreWishlist extends Component
{
  public $wishlistitems;
  protected $listeners = ['wishlistUpdated' => 'mount'];

  public function render()
  {
    $data = [
      'wishlistitems' => $this->wishlistItems
    ];
    return view('livewire.store-wishlist', $data);
  }
  public function reload()
  {
  }
  public function getWishlistItemsProperty()
  {
    $session_id = Session::getId();
    $wishlist = Wishlist::where('session_id', $session_id)->pluck('product_id')->toArray();
    return Product::whereIn('id', $wishlist)->get();
  }
  public function mount()
  {
    // Initial load of wishlistitems
    $this->wishlistitems = $this->getWishlistItemsProperty();
  }
}
