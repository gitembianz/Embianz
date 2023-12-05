<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Status;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Cart_Item;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Session;

class StoreHeader extends Component
{
  public $search = '';
  public $active = false;
  public $showwis = false;
  public $showcart = false;
  public $total;
  public $session_id;
  public $closedStatusId;
  protected $listeners = [
    'wishlistUpdated' => 'mount',
    'cartUpdated' => 'mount'
  ];

  public function render()
  {
    if ($this->active) {
      $data = [
        'categories' => $this->categories,
        'objects' => $this->objects,
        'cats' => $this->cats,
        'wishlistitems' => $this->wishlistitems,
        'cartItems' => $this->cartItems,
      ];
    } else {
      $data = [
        'categories' => $this->categories,
        'wishlistitems' => $this->wishlistitems,
        'cartItems' => $this->cartItems,
      ];
    }

    return view('livewire.store-header', $data);
  }

  private function getCookieId()
  {
    if (isset($_COOKIE['sessionId'])) {
      return $_COOKIE['sessionId'];
    }
    return Session::getId();
  }

  public function close()
  {
    $this->active = false;
    $this->search = '';
  }
  public function getWishlistitemsProperty()
  {
    $wishlist = Wishlist::where('session_id', $this->session_id)->pluck('product_id')->toArray();

    if (!empty($wishlist)) {
      return Product::whereIn('id', $wishlist)->with('media', 'media.location')->get();
    }

    return collect(); // Return an empty collection if $wishlist is empty
  }
  public function getCartItemsProperty()
  {
    $cart = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', $this->closedStatusId)
      ->latest()->first();

    if ($cart !== null) {
      $cartItems = Cart_Item::where('cart_id', $cart->id)->with('product.media.location', 'product.product_prices.pricelist.currency')->get();

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

    $this->session_id = $this->getCookieId();
    $this->total = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', $this->closedStatusId)
      ->latest()
      ->value('quantity_amount');
  }

  public function getCategoriesProperty()
  {
    // Retrieve the limit from the settings table
    $limitSetting = Store_Settings::where('parameter', 'limit_category')->first();

    // Check if the setting exists and has a valid numeric value
    $limit = $limitSetting && is_numeric($limitSetting->value) ? $limitSetting->value : 5;

    // Use eager loading to load relationships with constraints
    return $this->categoriesQuery
      ->limit($limit)
      ->with([
        'subcategory' => function ($query) {
          $query->whereHas('category', function ($subQuery) {
            $subQuery->where('store_tab', 1);
          })->with('category.media.location', 'category');
        }
      ])
      ->get();
  }


  public function getCategoriesQueryProperty()
  {
    return Category::where('active', true)->where('store_tab', '1');
  }
  public function getObjectsProperty()
  {
    return $this->objectsQuery->get();
  }
  public function getObjectsQueryProperty()
  {
    return Product::name($this->search)->where('active', true)->with('product_prices.pricelist.currency', 'media.location');
  }
  public function getCatsProperty()
  {
    return $this->catsQuery->get();
  }
  public function getCatsQueryProperty()
  {
    return Category::name($this->search)->where('active', true)->with('media.location');
  }

  public function continue()
  {
    $delivery = Store_Settings::where('parameter', 'delivery_price')->first()->value;
    $cart = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', $this->closedStatusId)
      ->latest()->first();
    $validatequantity = true;
    $cartitems = Cart_Item::where('cart_id', $cart->id)->get();
    if ($cartitems) {
      foreach ($cartitems as $item) {
        if ($item->quantity > $item->product->quantity) {
          $validatequantity = false;
          $this->dispatchBrowserEvent('alert__modal');
          return;
        }
      }
    }
    if ($validatequantity) {

      $newStatusId = Status::where('name', 'checkout')->where('type', 'cart')->first()->id;
      $cart->final_amount = $cart->sum_amount + $delivery;
      $cart->status_id = $newStatusId;
      $cart->delivery_price = $delivery;
      $cart->save();
      return redirect()->route('order');
    }
  }
}
