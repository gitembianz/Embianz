<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;

class StoreHeader extends Component
{
  public $search = '';
  public $active = false;
  public $cart;

  protected $listeners = [
    'newcart' => 'getCart',
    'orderprocess' => 'mount',
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
    $this->getCart();
  }
  public function getCart()
  {
    $this->cart = Cart::where('session_id', app('global_session_id'))
      ->where('status_id', '!=', app('global_cart_closed'))
      ->latest()->first();
    return $this->cart ?? [];
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
  public function showwis()
  {
    $this->emit('showwis');
  }

  public function getCategoriesProperty()
  {
    $limit = app('global_limit_category');

    return Category::where('active', 1)
      ->where('store_tab', '1')
      ->where('has_parrent', '0')
      ->with([
        'subcategory' => function ($query) {
          $query->with('category', function ($subQuery) {

            $subQuery->where('store_tab', 1)->where('active', 1);
          })->with([
            'category.media' => function ($query) {
              $query->where('type', 'min'); // Filter and limit the media relationship
            },
            'category.subcategory',
            'category.subcategory.category.media' => function ($query) {
              $query->where('type', 'min'); // Filter and limit the media relationship
            },
          ]);
        },
        'media' => function ($query) {
          $query->where('type', 'min'); // Filter and limit the media relationship
        }
      ])->limit($limit)->orderby('sequence')->get();
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
