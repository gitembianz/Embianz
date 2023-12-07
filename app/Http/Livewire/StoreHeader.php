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
  public $cart;
  public $wishlists;
  public $session_id;
  public $closedStatusId;
  protected $listeners = [
    'wishlistUpdated' => 'updatewis'
  ];

  public function render()
  {
    if ($this->active) {
      $data = [
        'categories' => $this->categories,
        'objects' => $this->objects,
        'cats' => $this->cats,
      ];
    } elseif ($this->showcart) {
      $data = [
        'categories' => $this->categories,
        'cartItems' => $this->cartItems,
      ];
    } else {
      $data = [
        'categories' => $this->categories,
      ];
    }

    return view('livewire.store-header', $data);
  }
  public function mount()
  {
    $this->session_id = $this->getCookieId();

    $this->updatecart();
    $this->updatewis();
  }
  public function updateCart()
  {
    $this->cart = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', Status::where('name', 'closed')->where('type', 'cart')->value('id'))
      ->latest()->first();

    // Ensure $this->cart is initialized as an empty array if it is null
    $this->cart = $this->cart ?? [];
  }
  public function updatewis()
  {
    $this->wishlists = Wishlist::where('session_id', $this->session_id)->with('product.media.location', 'product')->get();
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

  public function getCartItemsProperty()
  {
    if ($this->cart !== null) {
      $cartItems = Cart_Item::where('cart_id', $this->cart->id)->with('product.media.location', 'product.product_prices.pricelist.currency')->get();

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
    $product = Product::find($productId);

    if ($this->cart !== null) {
      $cart_item = Cart_Item::firstOrNew([
        'cart_id' => $this->cart->id,
        'product_id' => $productId,
      ]);

      if ($cart_item->exists) {
        $this->cart->quantity_amount -= $cart_item->quantity;
        $this->cart->sum_amount -= ($product->product_prices->first()->value * $cart_item->quantity);
        $this->cart->save();
        $cart_item->delete();
        $this->emit('cartUpdated');
      }
    }
  }




  public function getCategoriesProperty()
  {
    $limit = Store_Settings::where('parameter', 'limit_category')->value('value') ?? '5';

    return Category::where('active', 1)
      ->where('store_tab', '1')
      ->with([
        'subcategory' => function ($query) {
          $query->whereHas('category', function ($subQuery) {
            $subQuery->where('store_tab', 1);
          })->with(['category.media.location']);
        }
      ])
      ->limit($limit)->orderby('sequence')
      ->get();
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
    $validatequantity = true;
    $cartitems = Cart_Item::where('cart_id', $this->cart->id)->get();
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
      $this->cart->final_amount = $this->cart->sum_amount + $delivery;
      $this->cart->status_id = $newStatusId;
      $this->cart->delivery_price = $delivery;
      $this->cart->save();
      return redirect()->route('order');
    }
  }
}
