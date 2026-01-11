<?php

namespace App\Http\Livewire;

use App\Models\Wishlist;

class WishlistActions extends \Livewire\Component
{
  protected $listeners = [
    'wishlist:add' => 'add',
    'wishlist:remove' => 'remove',
  ];

  public function add($productId)
  {
    Wishlist::updateOrCreate([
      'session_id' => request()->cookie('sessionId') ?? session()->getId(),
      'product_id' => $productId,
    ]);

    $this->emit('wishlistUpdated');
  }

  public function remove($productId)
  {
    Wishlist::where('session_id', request()->cookie('sessionId') ?? session()->getId())
      ->where('product_id', $productId)
      ->delete();

    $this->emit('wishlistUpdated');
  }

  public function render()
  {
    return view('livewire.wishlist-actions');
  }
}
