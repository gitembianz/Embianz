<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;


class StoreHeader extends Component
{
  public $limit = 5;
  public $search = '';
  public $active = false;
  public $wishlistitems;
  public $showwis = false;
  public $showcart = false;
  protected $listeners = [
    'wishlistUpdated' => 'mount',
    'cartUpdated' => 'mount'
  ];

  public function render()
  {
    $data = [
      'categories' => $this->categories,
      'objects' => $this->objects,
      'cats' => $this->cats,
      'wishlistitems' => $this->wishlistItems,
      'cartitems' => $this->cartItems,
    ];

    return view('livewire.store-header', $data);
  }
  public function close()
  {
    $this->active = false;
    $this->search = '';
  }
  public function getWishlistItemsProperty()
  {
    $session_id = Session::getId();
    $wishlist = Wishlist::where('session_id', $session_id)->pluck('product_id')->toArray();
    return Product::whereIn('id', $wishlist)->get();
  }
  public function refreshWishlist()
  {
    // Update the wishlistitems property here
    $this->wishlistitems = $this->getWishlistItemsProperty();
  }

  public function getCartItemsProperty()
  {
    $session_id = Session::getId();
    $cart = Cart::where('session_id', $session_id)->pluck('product_id')->toArray();
    return Product::whereIn('id', $cart)->get();
  }

  public function wishlistshow()
  {
    if ($this->showwis === false) {
      $this->showwis = true;
    } else {
      $this->showwis = false;
    }
  }
  public function cartshow()
  {
    if ($this->showcart === false) {
      $this->showcart = true;
    } else {
      $this->showcart = false;
    }
  }
  public function reload()
  {
    // No need to add any code here, just an empty method
  }

  public function removeFromWishlist($productId)
  {
    $session_id = Session::getId();
    Wishlist::where('session_id', $session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('wishlistUpdated');
  }
  public function removeFromCart($productId)
  {
    $session_id = Session::getId();
    Cart::where('session_id', $session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('cartUpdated');
  }
  public function mount()
  {
    // Initial load of wishlistitems
    $this->wishlistitems = $this->getWishlistItemsProperty();
  }

  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->limit($this->limit)->get();
  }
  public function getCategoriesQueryProperty()
  {
    return Category::orderBy('store_tab', 'desc')->with('subcategory');
  }
  public function getObjectsProperty()
  {
    return $this->objectsQuery->get();
  }
  public function getObjectsQueryProperty()
  {
    return Product::name($this->search)->with('product_prices')->with('media');
  }
  public function getCatsProperty()
  {
    return $this->catsQuery->get();
  }
  public function getCatsQueryProperty()
  {
    return Category::name($this->search)->with('media');
  }
}
