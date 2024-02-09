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

  protected $listeners = [
    'newcart' => 'getCartProperty',
    'orderprocess' => 'getCartProperty',
  ];

  public function render()
  {
    if ($this->active) {
      $data = [
        'categories' => $this->categories,
        'objects' => $this->objects,
        'cats' => $this->cats,
        'cart' => $this->cart,

      ];
    } else {
      $data = [
        'categories' => $this->categories,
        'cart' => $this->cart,

      ];
    }
    return view('livewire.store-header', $data);
  }

  public function getCartProperty()
  {
    return Cart::where('session_id', app('global_session_id'))
      ->where('status_id', '!=', app('global_cart_closed'))
      ->latest()->first() ?? [];
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
      ])->limit(app('global_limit_category'))->orderby('sequence')->get();
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
