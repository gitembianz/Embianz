<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;

class StoreWishlist extends Component
{
  protected $listeners = ['wishlistUpdated' => 'mount'];


  public function render()
  {
    $data = [
      'wishlistitems' => $this->wishlistItems
    ];
    return view('livewire.store-wishlist', $data);
  }
  public function removeFromWishlist($productId)
  {
    Wishlist::where('session_id', app('global_session_id'))
      ->where('product_id', $productId)
      ->delete();
    $this->emit('wishlistUpdated');
  }
  public function getWishlistItemsProperty()
  {
    $wishlist = Wishlist::where('session_id', app('global_session_id'))->pluck('product_id')->toArray();
    return Product::whereIn('id', $wishlist)->with([
      'media' => function ($query) {
        $query->where('type', 'min'); // Filter and limit the media relationship
      }
    ])->get();
  }
}
