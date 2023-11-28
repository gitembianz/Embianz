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
use App\Models\Subcategory;
use Illuminate\Support\Facades\Session;

class StoreMain extends Component
{
  public $limit = 10;
  public $slider;
  public $category;
  public $quantity = 10;
  public $session_id;
  public $wishlist = [];
  public $isLoading = true;
  protected $listeners = [
    'wishlistUpdated' => 'mount'
  ];
  private function getCookieId()
  {
    if (isset($_COOKIE['sessionId'])) {
      return $_COOKIE['sessionId'];
    }
    return Session::getId();;
  }
  public function mount()
  {
    $this->session_id = $this->getCookieId();
    if (now()->diffInHours($this->lastUpdated($this->session_id)) >= 24) {
      $this->isLoading = true;
    } else {
      $this->isLoading = false;
    }
    $sliderCategory = Store_Settings::where('parameter', 'slider_category')->first();

    if ($sliderCategory) {
      $categoryId = $sliderCategory->value;
      $this->category = Category::find($categoryId);
    } else {
      $this->category = null;
    }
  }
  private function lastUpdated()
  {
    return now();
  }
  public function getPopProductsProperty()
  {
    return $this->popproductsQuery->limit($this->limit)->get();
  }
  public function getPopProductsQueryProperty()
  {
    return Product::where('active', true)->orderBy('popularity', 'desc')->with('media.location')->with('product_prices.pricelist.currency');
  }
  public function render()
  {
    return view('livewire.store-main', [
      'popproducts' => $this->popproducts,
      'subcategories' => $this->subcategories
    ]);
  }


  public function getSubcategoriesProperty()
  {
    if ($this->category) {
      return Subcategory::where('parrent_id', $this->category->id)
        ->with('category.media.location')
        ->get();
    }
  }



  public function addToWishlist($productId)
  {
    if (!in_array($productId, $this->wishlist)) {
      $this->wishlist[] = $productId;
      $this->saveToSession();

      Wishlist::updateOrCreate(
        ['session_id' => $this->session_id, 'product_id' => $productId]
      );
      $this->emit('wishlistUpdated');
    }
  }
  public function removeFromWishlist($productId)
  {
    $this->wishlist = array_diff($this->wishlist, [$productId]);
    $this->saveToSession();

    Wishlist::where('session_id', $this->session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('wishlistUpdated');
  }
  private function saveToSession()
  {
    session([
      'wishlist' => $this->wishlist,
    ]);
  }
  public function toggleWishlist($productId)
  {
    if (in_array($productId, $this->wishlist)) {
      $this->removeFromWishlist($productId);
    } else {
      $this->addToWishlist($productId);
    }
  }
  public function addToCart($productId)
  {
    $closedStatusId = Status::where('name', 'closed')->where('type', 'cart')->first()->id;
    $product = Product::with('product_prices.pricelist')->find($productId);
    $newStatusId = Status::where('name', 'new')->where('type', 'cart')->first()->id;
    $cart = Cart::where('session_id', $this->session_id)->where('status_id', '!=', $closedStatusId)->latest()->first();
    if (!$cart) {
      $baseName = class_basename(Cart::class);
      $cartNumber = 1;
      $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
      while (Cart::where('name', $uniqueName)->exists()) {
        $cartNumber++;
        $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);
      }
      $cart = Cart::create([
        'session_id' => $this->session_id,
        'name' => $uniqueName,
        'quantity_amount' => 0,
        'sum_amount' => 0,
        'status_id' => $newStatusId,
        'currency_id' => $product->product_prices->first()->pricelist->currency_id,
      ]);
    }
    $cartItem = Cart_Item::where('cart_id', $cart->id)->where('product_id', $productId)->first();
    if (!$cartItem) {
      $cartItem = Cart_Item::create([
        'cart_id' => $cart->id,
        'product_id' => $productId,
        'price' => $product->product_prices->first()->value,
        'quantity' => 1
      ]);
      $cart->increment('quantity_amount');
      $cart->sum_amount += $product->product_prices->first()->value;
    } else {
      if ($cartItem->quantity < $product->quantity) {

        $cartItem->increment('quantity');
        $cart->increment('quantity_amount');
        $cart->sum_amount += $product->product_prices->first()->value;
      }
    }
    $cart->save();
    $this->emit('cartUpdated');
  }
}
