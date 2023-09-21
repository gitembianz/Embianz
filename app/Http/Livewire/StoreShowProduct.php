<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class StoreShowProduct extends Component
{
  public $record;
  public $productId;
  public $activeTab = 0;
  public $quantity;
  public $limit = null;
  public $maxlimit = null;
  public $mainpath = null;
  public $path = null;
  public $relatedphotos = [];
  public $mainimages;
  public $mainMedia;

  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->record

    ]);
  }

  public function switchTab($index)
  {
    $this->activeTab = $index;
  }
  public function updateCounterValue()
  {
    $this->quantity = $this->quantity;
  }

  public function selectpath($id)
  {
    $this->path = '1';
    $this->mainpath = $id;
  }


  public function incrementCounter()
  {
    if ($this->quantity >= $this->limit) {
      $this->maxlimit = true;
      $this->quantity = $this->limit;
    } else {
      $this->quantity++;
    }
  }
  public  function decrementCounter()
  {

    if ($this->quantity > 1) {
      if ($this->quantity == $this->limit) {
        $this->maxlimit = false;
      }
      $this->quantity--;
    }
  }
  public function modal($id)
  {
    dd($id);
  }
  public function mount()
  {
    $this->record = Product::findOrFail($this->productId);
    $this->limit = $this->record->quantity;
    $this->quantity = 1;

    $this->mainMedia = $this->record->media->firstWhere('location.location', 'main');
    if ($this->mainMedia) {
      if (!$this->path) {
        $this->mainpath = $this->mainMedia->external
          ? $this->mainMedia->path
          : "/{$this->mainMedia->path}{$this->mainMedia->name}";
      }
    }

    $this->mainimages = $this->record->media
      ->where('location.location', '!=', 'search')
      ->sortBy('location_id')
      ->values();

    $this->relatedphotos = $this->mainimages->map(function ($image) {
      return $image->external
        ? $image->path
        : "/{$image->path}{$image->name}";
    });
  }




  public function getProductProperty()
  {
    return $this->productQuery;
  }
  public function getProductQueryProperty()
  {
    return Product::find($this->productId);
  }
}
