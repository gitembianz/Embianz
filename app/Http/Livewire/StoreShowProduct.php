<?php

namespace App\Http\Livewire;


use App\Models\Product;
use Livewire\Component;


class StoreShowProduct extends Component
{
  public $productId;
  public $quantity;
  public $session_id;
  public $back = false;

  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product
    ]);
  }
  private function getSessionId()
  {
    if (array_key_exists('sessionId', $_COOKIE)) {
      return $_COOKIE['sessionId'];
    } else {
      return session()->getId();
    }
  }
  public function mount($productId)
  {
    $this->productId = $productId;
    $this->session_id = $this->getSessionId();
    $this->quantity = app('global_low_stock');
  }


  public function getProductProperty()
  {
    return Product::select('id', 'name', 'seo_id', 'long_description',)
      ->with([
        'media' => function ($query) {
          $query->select('name', 'path', 'type', 'sequence')
            ->whereIn('type', ['full', 'original'])
            ->orderBy('sequence');
        },
        'product_specs' => function ($query) {
          $query->select('product_id', 'spec_id', 'value', 'id')->with('spec:id,name');
        },
        'related_product' => function ($query) {
          $query->orderBy('sequence')->select('parent_id', 'product_id', 'sequence', 'id')->with([
            'product' => function ($query) {
              $query->where('active', 1)->where('start_date', '<=',  now()->format('Y-m-d'))
                ->where('end_date', '>=',  now()->format('Y-m-d'))->select('id', 'name', 'popularity', 'seo_id', 'short_description', 'quantity', 'active', 'end_date', 'start_date')->with([
                  'media' => function ($query) {
                    $query->select('path', 'name')->where('type', 'main');
                  },
                  'product_prices' => function ($query) {
                    $query->select('product_id', 'value', 'discount', 'value_no_discount');
                  },
                  'wishlists' => function ($query) {
                    $query->select('id', 'product_id')->where('session_id', $this->session_id);
                  }
                ]);
            }
          ]);
        }
      ])->where('id', $this->productId)->first();
  }
}
