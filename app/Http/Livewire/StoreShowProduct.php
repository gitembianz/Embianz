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

  public function render()
  {
    return view('livewire.store-show-product', [
      'product' => $this->product,
      'medias' => $this->medias
    ]);
  }
  public function switchTab($index)
  {
    $this->activeTab = $index;
  }
  public function mount()
  {
    $this->record = Product::findorfail($this->productId);
    $productType = class_basename(get_class($this->record));
    $tabel_id = Tabels::where('name', $productType)->first()->id;
    $this->medias = Media::where('tabel_id', $tabel_id)->where('item_id', $this->productId)->get();
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