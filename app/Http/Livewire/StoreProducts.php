<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Models\MediaLocation;
use App\Models\Tabels;
use App\Models\Product;
use Livewire\Component;

class StoreProducts extends Component
{

  public $totalRecords;
  public $loadAmount = 9;
  public $medias = [];
  public $product;

  public function loadMore()
  {
    $this->loadAmount += 10;
  }

  public function mount()
  {
    $this->product = new Product();
    $this->totalRecords = Product::count();
    $productType = class_basename(get_class($this->product));
    $location = MediaLocation::where('location', 'main')->first()->id;
    $tabel_id = Tabels::where('name', $productType)->first()->id;
    $this->medias = Media::where('tabel_id', $tabel_id)->where('location_id', $location)->get();
  }
  public function render()
  {
    return view('livewire.store-products', [
      'products' => $this->products,
      'medias' => $this->medias
    ]);
  }
  public function getProductsProperty()
  {
    return $this->productsQuery->limit($this->loadAmount)->get();
  }
  public function getProductsQueryProperty()
  {
    return Product::orderBy('created_at', 'desc');
  }
}
