<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Models\Tabels;
use App\Models\Product;
use Livewire\Component;

class StoreShowProduct extends Component
{
  public  $medias = [];
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
      'product' => $this->record,
      'medias' => $this->medias
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

    $productType = class_basename(get_class($this->record));
    $tabel = Tabels::where('name', $productType)->first();

    if ($tabel) {
      $tabelId = $tabel->id;
      $this->medias = Media::where('tabel_id', $tabelId)
        ->where('item_id', $this->productId)
        ->get();

      $this->mainMedia = $this->medias->firstWhere('location.location', 'main');
      if ($this->mainMedia) {
        if (!$this->path) {
          $this->mainpath = $this->mainMedia->external
            ? $this->mainMedia->path
            : "/{$this->mainMedia->path}{$this->mainMedia->name}";
        }
      }

      $this->mainimages = $this->medias
        ->where('location.location', '!=', 'search')
        ->sortBy('location_id')
        ->values();

      $this->relatedphotos = $this->mainimages->map(function ($image) {
        return $image->external
          ? $image->path
          : "/{$image->path}{$image->name}";
      });
    }
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
