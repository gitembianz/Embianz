<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Status;
use App\Models\Cart_Item;
use App\Models\Category;
use App\Models\Product;
use App\Models\Specs;
use App\Models\Wishlist;
use Livewire\Component;
use Livewire\WithPagination;

class StoreProducts extends Component
{
  use WithPagination;

  public $loadAmount = 9;
  public $search = "";
  public $quantity = 20;
  public $wishlist = [];
  public $session_id;
  public $specification;
  public $orderBy = 'best_selling'; // Default sorting order
  public $orderAsc = true;
  public $products;
  public $category;
  public $categoryname;
  public $property = false;
  public $specfilter = false;
  public $selectedSpecValues = [];
  public $selectedKeys = [];
  public $selectedSpecNames = [];

  protected $listeners = [
    'wishlistUpdated' => 'mount',
    'cartUpdated' => 'mount'
  ];

  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function render()
  {
    $this->products = $this->getProducts();
    return view('livewire.store-products');
  }
  public function mount()
  {
    $this->session_id = $_COOKIE['sessionId'];

    $this->specification = Specs::with('product_spec')->get();
  }
  public function getUniqueSpecValues($specId)
  {
    $uniqueValues = [];

    foreach ($this->specification as $spec) {
      if ($spec->id === $specId) {
        foreach ($spec->product_spec as $value) {
          $uniqueValues[] = $value->value;
        }
        break; // Exit the loop once the specific spec is found and processed
      }
    }

    return array_unique($uniqueValues);
  }
  public function resetFilter()
  {
    $this->selectedSpecValues = [];
    $this->selectedSpecNames = [];
    $this->selectedKeys = [];
    $this->property = false;
  }
  public function applyFilter()
  {
    $this->selectedSpecNames = [];
    $filteredValues = array_filter($this->selectedSpecValues, function ($values) {
      return in_array(true, $values);
    });
    // Extract keys where the value is true
    $allKeys = array_keys(array_merge(...$filteredValues));
    $this->selectedKeys = $allKeys;
    foreach ($this->specification as $spec) {
      foreach ($this->selectedKeys as $key) {
        foreach ($spec->product_spec as $value) {
          if ($value->value == $key) {
            $this->selectedSpecNames[$key] = $spec->name;
          }
        }
      }
    }

    if (isset($this->selectedKeys)) {
      $this->specfilter = true;
      $this->property = false;
    }
  }
  public function removeSpec($key)
  {

    foreach ($this->selectedSpecValues as &$subarray) {
      if (isset($subarray[$key])) {
        unset($subarray[$key]);
        if (empty($subarray)) {
          unset($subarray);
        }
        break;
      }
    }
    unset($this->selectedSpecNames[$key]);
    $allKeys = array_keys(array_merge(...$this->selectedSpecValues));
    $this->selectedKeys = $allKeys; // Update selectedKeys
  }
  public function clearall()
  {
    $this->selectedSpecValues = [];
    $this->selectedSpecNames = [];
    $this->selectedKeys = [];
  }
  public function getProducts()
  {
    $query = Product::name($this->search)->with('media.location')->with('product_prices.pricelist.currency')->with('wishlists');
    if ($this->category) {
      $this->categoryname = Category::find($this->category)->name;
      $query->whereHas('product_categories.category', function ($query) {
        $query->where('id', $this->category);
      });
    }

    if ($this->specfilter) {
      foreach ($this->selectedKeys as $value) {
        $query->whereHas('product_specs', function ($query) use ($value) {
          $query->where('value', $value);
        });
      }
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
    $closedStatusId = Status::where('name', 'closed')->where('type', 'cart')->first()->id;
    $product = Product::with('product_prices.pricelist')->find($productId);
    $newStatusId = Status::where('name', 'new')->where('type', 'cart')->first()->id;
    $cart = Cart::where('session_id', $this->session_id)->where('status_id', '!=', $closedStatusId)->latest()->first();

    if (!$cart) {
      $baseName = class_basename(Cart::class); // Gets the base name of the Cart model class (e.g., "Cart")
      $cartNumber = 1;
      $uniqueName = $baseName . '_' . str_pad($cartNumber, 2, '0', STR_PAD_LEFT);

      // Check for uniqueness, generate a new name if it's not unique
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
    } else {
      if ($cartItem->quantity < $product->quantity) {

        $cartItem->increment('quantity');
      }
    }

    $cart->increment('quantity_amount');
    $cart->sum_amount += $product->product_prices->first()->value;
    $cart->save();

    $this->emit('cartUpdated');
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
