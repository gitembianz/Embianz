<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\Status;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Store_Settings;

class StoreHeader extends Component
{
  public $search = '';
  public $active = false;
  public $cart;
  public $session_id;

  protected $listeners = [
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
      $sessionId = session()->getId();
      setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);
      $this->session_id = $sessionId;
    }
    $this->updatecart();
  }

  public function updateCart()
  {
    $this->cart = Cart::where('session_id', $this->session_id)
      ->where('status_id', '!=', Status::where('name', 'closed')->where('type', 'cart')->value('id'))
      ->latest()->first();
    $this->cart = $this->cart ?? [];
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
    $limit = Store_Settings::where('parameter', 'limit_category')->value('value') ?? '5';

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

  // public function getCategoriesProperty()
  // {
  //   $limit = Store_Settings::where('parameter', 'limit_category')->value('value') ?? '5';

  //   return Category::where('active', 1)
  //     ->where('store_tab', '1')
  //     ->where('has_parrent', '0')
  //     ->with([
  //       'subcategory' => function ($query) {
  //         $query->whereHas('category', function ($subQuery) {

  //           $subQuery->where('store_tab', 1)->where('active', 1);
  //         })->with([
  //           'category.media' => function ($query) {
  //             $query->where('type', 'min'); // Filter and limit the media relationship
  //           },
  //           'category.subcategory'
  //         ]);
  //       },
  //       'media' => function ($query) {
  //         $query->where('type', 'min'); // Filter and limit the media relationship
  //       }
  //     ])->limit($limit)->orderby('sequence')->get();
  // }

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
