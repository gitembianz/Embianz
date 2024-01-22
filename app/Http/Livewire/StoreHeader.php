<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Status;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\Store_Settings;

class StoreHeader extends Component
{
  public $search = '';
  public $active = false;
  public $showwis = false;
  // public $showcart = false;
  public $total;
  public $cart;
  public $wishlists;
  public $session_id;
  public $closedStatusId;
  protected $listeners = [
    'wishlistUpdated' => 'updatewis',
    'newcart' => 'mount'
  ];

  public function render()
  {
    if ($this->active) {
      $data = [
        'categories' => $this->categories,
        'objects' => $this->objects,
        'cats' => $this->cats,
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
    if (array_key_exists('sessionId', $_COOKIE)) {
      $this->session_id = $_COOKIE['sessionId'];
    } else {
      // If not present, generate a new sessionId
      $sessionId = session()->getId();

      // Set the new sessionId in the cookie
      setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);

      $this->session_id = $sessionId;
    }
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
    $this->wishlists = Wishlist::where('session_id', $this->session_id)->with('product.media', 'product')->get();
  }

  public function close()
  {
    $this->active = false;
    $this->search = '';
  }

  public function showcart()
  {
    $this->emit('showcart');
  }

  public function wishlistshow()
  {
    if ($this->showwis === false) {
      // $this->showcart = false;
      $this->showwis = true;
    } else {
      $this->showwis = false;
    }
  }

  public function removeFromWishlist($productId)
  {
    Wishlist::where('session_id', $this->session_id)
      ->where('product_id', $productId)
      ->delete();
    $this->emit('wishlistUpdated');
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
          })->with([
            'category.media' => function ($query) {
              $query->where('type', 'min'); // Filter and limit the media relationship
            }
          ]);
        }
      ])
      ->limit($limit)->orderby('sequence')
      ->get();
  }


  public function getObjectsProperty()
  {
    return Product::name($this->search)->where('active', true)->with([
      'media' => function ($query) {
        $query->where('type', 'min'); // Filter and limit the media relationship
      },
      'product_prices.pricelist.currency'
    ])->get();
  }

  public function getCatsProperty()
  {
    return Category::name($this->search)->where('active', true)->with([
      'media' => function ($query) {
        $query->where('type', 'min'); // Filter and limit the media relationship
      }
    ])->get();
  }
}
