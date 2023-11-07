<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Store_Settings;

class StoreMain extends Component
{
  public $limit = 10;
  public $slider;
  public $category;
  public $session_id;
  public $wishlist = [];
  protected $listeners = [
    'wishlistUpdated' => 'mount'
  ];

  public function mount()
  {
    $this->session_id = $_COOKIE['sessionId'];
    $sliderCategory = Store_Settings::where('parameter', 'slider_category')->first();

    if ($sliderCategory) {
      $categoryId = $sliderCategory->value;
      $this->category = Category::find($categoryId);
    } else {
      $this->category = null;
    }
  }
  public function render()
  {
    return view('livewire.store-main', [
      'popproducts' => $this->popproducts,
      'category' => $this->category
    ]);
  }
  public function getPopProductsProperty()
  {
    return $this->popproductsQuery->limit($this->limit)->get();
  }
  public function getPopProductsQueryProperty()
  {
    return Product::orderBy('popularity', 'desc')->with('media.location')->with('product_prices.pricelist.currency');
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
}
