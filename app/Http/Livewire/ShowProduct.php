<?php

namespace App\Http\Livewire;

use App\Models\PricelistEntries;
use App\Models\Product;
use App\Models\Product_Spec;
use Livewire\Component;
use App\Models\Products_categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ShowProduct extends Component
{

  public $productId;
  public $editproduct = null;
  public $prod;

  public function mount($productId)
  {
    $this->productId = $productId;
  }
  public function confirmProductRemoval($id)
  {
    $this->productId = $id;
    $this->dispatchBrowserEvent('show-delete-modal-category');
  }
  public function editproduct()
  {
    $this->prod = [
      'product_name' => $this->product->name,
      'active' => $this->product->active,
      'product_status' => $this->product->product_status,
      'start_date' => $this->product->start_date,
      'end_date' => $this->product->end_date,
      'popularity' => $this->product->popularity,
      'short_description' => $this->product->short_description,
      'long_description' => $this->product->long_description,
      'seo_title' => $this->product->seo_title,
      'quantity' => $this->product->quantity,
      // Add other properties as needed
    ];
    $this->editproduct = true;
  }
  public function getProductProperty()
  {
    return $this->productQuery;
  }
  public function getProductQueryProperty()
  {
    return Product::find($this->productId);
  }
  public function saveproduct()
  {
    $product_new = $this->prod ?? NULL;
    if (!is_null($product_new)) {
      $new = Product::find($this->productId);
      if (array_key_exists('product_name', $product_new)) {
        $new->name = $product_new['product_name'];
      }
      if (array_key_exists('product_status', $product_new)) {
        $new->product_status = $product_new['product_status'];
      }
      if (array_key_exists('start_date', $product_new)) {
        $new->start_date = $product_new['start_date'];
      }
      if (array_key_exists('active', $product_new)) {
        $new->active = $product_new['active'];
      }
      if (array_key_exists('end_date', $product_new)) {
        $new->end_date = $product_new['end_date'];
      }
      if (array_key_exists('quantity', $product_new)) {
        $new->quantity = $product_new['quantity'];
      }
      if (array_key_exists('short_description', $product_new)) {
        $new->short_description = $product_new['short_description'];
      }
      if (array_key_exists('popularity', $product_new)) {
        $new->popularity = $product_new['popularity'];
      }
      if (array_key_exists('long_description', $product_new)) {
        $new->long_description = $product_new['long_description'];
      }
      if (array_key_exists('seo_title', $product_new)) {
        $new->seo_title = $product_new['seo_title'];
      }
      $new->last_modified_by = Auth::user()->name;
      $new->updated_at = now();
      $new->save();
      $this->emit('itemSaved');
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
    $this->prod = [];
    $this->editproduct = null;
  }
  public function updated()
  {
    $this->dispatchBrowserEvent('tabNavigation');
  }
  public function cancelproduct()
  {
    $this->editproduct = null;
    $this->prod = [];
  }
  public function deleteSingleRecord()
  {
    $id = $this->productId;
    $product = Product::find($id);
    $productcats = Products_categories::where('product_id', $id)->get();
    if ($productcats != NULL) {
      foreach ($productcats as $productcat) {
        $productcat->delete();
      }
    }
    $productspecs = Product_Spec::where('product_id', $id)->get();
    if ($productspecs != NULL) {
      foreach ($productspecs as $productspec) {
        $productspec->delete();
      }
    }
    $productpricelists = PricelistEntries::where('product_id', $id)->get();
    if ($productpricelists != NULL) {
      foreach ($productpricelists as $productpricelist) {
        $productpricelist->delete();
      }
    }
    $productType = class_basename(get_class($product));
    $filespath = 'media/' . $productType . '/' . $product->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }
    $product->delete();
    return redirect()->route('products')->with('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function render()
  {
    return view('livewire.show-product', [
      'product' => $this->product
    ]);
  }
}
