<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Status;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
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
  // public $isLoading = true;

  public function mount()
  {
    $this->session_id = $_COOKIE['sessionId'];
    $sliderCategory = Store_Settings::where('parameter', 'slider_category')->value('value');

    if ($sliderCategory) {
      $this->category = Category::find($sliderCategory);
    } else {
      $this->category = null;
    }
  }
  public function getPopProductsProperty()
  {
    return $this->popproductsQuery
      ->with([
        'media.location',
        'product_prices' => function ($query) {
          $query->with('pricelist.currency');
        },
        'wishlists'
      ])
      ->limit($this->limit)
      ->get();
  }

  public function getPopProductsQueryProperty()
  {
    return Product::where('active', true)
      ->orderBy('popularity', 'desc');
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
    if ($this->category != null) {
      return Subcategory::where('parrent_id', $this->category->id)
        ->with([
          'category' => function ($query) {
            $query->select('id', 'name', 'short_description');
          },
          'category.media' => function ($query) {
            $query->whereHas('location', function ($locationQuery) {
              $locationQuery->where('location', 'details');
            })->select('external', 'path', 'name');
          },
        ])
        ->get();
    }
    return collect();
  }

  public function addToCart($productId)
  {
    $closedStatusId = Status::where('name', 'closed')->where('type', 'cart')->value('id');
    $product = Product::with('product_prices.pricelist')->find($productId);
    $newStatusId = Status::where('name', 'new')->where('type', 'cart')->value('id');
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
      $this->emit('newcart');
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
