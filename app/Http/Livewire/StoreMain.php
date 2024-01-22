<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Store_Settings;
use App\Models\Subcategory;

class StoreMain extends Component
{
  public $limit = 10;
  public $slider;
  public $category;
  public $quantity = 10;
  public $session_id;

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
    $sliderCategory = Store_Settings::where('parameter', 'slider_category')->value('value');

    if ($sliderCategory) {
      $this->category = Category::find($sliderCategory);
    } else {
      $this->category = null;
    }
  }

  public function getPopProductsProperty()
  {
    return Product::where('active', true)
      ->orderBy('popularity', 'desc')
      ->with([
        'media' => function ($query) {
          $query->where('type', 'main'); // Filter and limit the media relationship
        },
        'product_prices' => function ($query) {
          $query->with('pricelist.currency');
        },
        'wishlists'
      ])
      ->limit($this->limit)
      ->get();
  }

  public function render()
  {
    if ($this->category != null) {
      return view('livewire.store-main', [
        'popproducts' => $this->popproducts,
        'subcategories' => $this->subcategories
      ]);
    } else {
      return view('livewire.store-main', [
        'popproducts' => $this->popproducts
      ]);
    }
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
            $query->where('type', 'original'); // Filter and limit the media relationship
          }
        ])
        ->get();
    }
    return collect();
  }
}
