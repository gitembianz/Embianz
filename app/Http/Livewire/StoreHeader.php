<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Status;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart_Item;
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
  public $cookieConsent;
  public $cookieId;
  public $session_id;
  public $closedStatusId;
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

  private function getCookieId()
  {
    if (isset($_COOKIE['sessionId'])) {
      return $_COOKIE['sessionId'];
    }
    return Session::getId();
  }
  private function saveSessionId()
  {
    $sessionId = session()->getId();
    setcookie('sessionId', $sessionId, time() + (30 * 24 * 60 * 60), '/');
    $this->emit('updateCookieConsent', $sessionId);
    $this->cookieId = true;
  }
  public function close()
  {
    $this->active = false;
    $this->search = '';
  }
  public function getWishlistItemsProperty()
  {
    $wishlist = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();
    return Product::whereIn('id', $wishlist)->with('media')->get();
  }
  public function refreshWishlist()
  {
    // Update the wishlistitems property here
    $this->wishlistitems = $this->getWishlistItemsProperty();
  }
  public function getCartItemsProperty()
  {
    $cart = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', $this->closedStatusId)
      ->latest()->first();

    if ($cart !== null) {
      $cartItems = Cart_Item::where('cart_id', $cart->id)->with('product.media.location')->with('product.product_prices.pricelist.currency')->get();

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
    if ($this->showwis === true) {
      $this->showwis = false;
    }
    if ($this->showcart == true) {
      $this->showcart = false;
    } else {
      $this->showcart = true;
    }
  }
  public function removeFromWishlist($productId)
  {
    Wishlist::where('session_id', $this->session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('wishlistUpdated');
  }
  public function removeFromCart($productId)
  {
    $cart = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', $this->closedStatusId)
      ->latest()->first();
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
    $this->closedStatusId = Status::where('name', 'closed')->where('type', 'cart')->first()->id;
    $this->cookieId = $this->getCookieId();

    if (!$this->cookieId) {
      $this->saveSessionId();
    }
    $this->session_id = $this->getCookieId();
    $this->total = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', $this->closedStatusId)
      ->latest()
      ->value('quantity_amount');
    $this->wishlistitems = $this->getWishlistItemsProperty();
  }
  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->limit($this->limit)->get();
  }
  public function getCategoriesQueryProperty()
  {
    return Category::where('active', '1')->where('store_tab', '1')->with('subcategory');
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
