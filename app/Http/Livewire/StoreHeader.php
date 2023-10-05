<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Cart_Item;
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
  public $total;
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
      'cartItems' => $this->cartItems,
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
    $cart = Cart::where('session_id', $session_id)->first();

    if ($cart !== null) {
      $cartItems = Cart_Item::where('cart_id', $cart->id)->with('product')->get();

      return $cartItems;
    }

    return collect(); // Return an empty collection if no cart items are found
  }
  public function wishlistshow()
  {
    if ($this->showwis === false) {
      $this->showcart = false;
      $this->showwis = true;
    } else {
      $this->showwis = false;
    }
  }
  public function cartshow()
  {
    if ($this->showcart === false) {
      $this->showwis = false;
      $this->showcart = true;
    } else {
      $this->showcart = false;
    }
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
    $cart = Cart::where('session_id', $session_id)->first();
    $product = Product::find($productId);

    if ($cart !== null) {
      $cart_item = Cart_Item::firstOrNew([
        'cart_id' => $cart->id,
        'product_id' => $productId,
      ]);

      if ($cart_item->exists) {
        $cart->quantity_amount -= $cart_item->quantity;
        $cart->sum_amount -= ($product->product_prices->first()->value * $cart_item->quantity);
        $cart->save();
        $cart_item->delete();
        $this->emit('cartUpdated');
      }
    }
  }
  public function mount()
  {
    $session_id = Session::getId();

    // Use sum() method to calculate the total quantity
    $this->total = Cart::where('session_id', $session_id)->sum('quantity_amount');
    $this->wishlistitems = $this->getWishlistItemsProperty();
  }
  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->limit($this->limit)->get();
  }
  public function getCategoriesQueryProperty()
  {
    return Category::orderBy('sequence', 'asc')->orderBy('store_tab', 'desc')->with('subcategory');
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