<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Specs;
use App\Models\Wishlist;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount = 9;
  public $search = "";
  public $quantity = 20;
  public $wishlist = [];
  public $cart = [];
  public $session_id;
  public $specification;
  public $property = false;
  public $selectedSpecValues = [];
  public $orderBy = 'best_selling'; // Default sorting order
  public $orderAsc = true;
  public $specfilter = false;
  public $products;
  public $category;
  public $categoryname;

  protected $listeners = [
    'wishlistUpdated' => 'mount',
    'cartUpdated' => 'mount'
  ];

  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function mount()
  {
    $this->session_id = Session::getId();
    $this->specification = Specs::all();
  }
  public function render()
  {
    $this->products = $this->getProducts();
    return view('livewire.store-products');
  }

  public function clearcategory()
  {
    $this->category = null;
    return redirect('/storeproducts');
  }
  public function getProducts()
  {

    $query = Product::name($this->search);
    if ($this->category) {
      $this->categoryname = Category::find($this->category)->name;
      $query->whereHas('product_categories.category', function ($query) {
        $query->where('id', $this->category);
      });
    }
    switch ($this->orderBy) {
      case 'best_selling':
        $query->orderBy('popularity', $this->orderAsc ? 'asc' : 'desc');
        break;
      case 'name_az':
        $query->orderBy('name', $this->orderAsc ? 'asc' : 'desc');
        break;
      case 'name_za':
        $query->orderBy('name', $this->orderAsc ? 'desc' : 'asc');
        break;
      case 'date_old_new':
        $query->orderBy('created_at', $this->orderAsc ? 'asc' : 'desc');
        break;
      case 'date_new_old':
        $query->orderBy('created_at', $this->orderAsc ? 'desc' : 'asc');
        break;
    }

    return $query->limit($this->loadAmount)->get();
  }
  // public function applyFilter()
  // {
  //   $this->specfilter = true;
  // }

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
  public function addToCart($productId)
  {
    if (!in_array($productId, $this->cart)) {
      $this->cart[] = $productId;
      $this->saveToSession();
      $quantity = 1;

      Cart::updateOrCreate(
        [
          'session_id' => $this->session_id,
          'product_id' => $productId,
          'quantity' => $quantity
        ],
      );
      $this->emit('cartUpdated');
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

  public function removeFromCart($productId)
  {
    $this->cart = array_diff($this->cart, [$productId]);
    $this->saveToSession();

    Cart::where('session_id', $this->session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('cartUpdated');
  }

  private function saveToSession()
  {
    session([
      'wishlist' => $this->wishlist,
      'cart' => $this->cart
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
  public function toggleCart($productId)
  {
    if (in_array($productId, $this->cart)) {
      $this->removeFromCart($productId);
    } else {
      $this->addToCart($productId);
    }
  }
}
