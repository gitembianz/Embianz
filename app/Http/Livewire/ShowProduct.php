<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Products_categories;
use Illuminate\Support\Facades\File;

class ShowProduct extends Component
{

  public $productId;
  public $product;
  public $editproduct = null;

  public function mount($productId)
  {
      $this->productId = $productId;
      $this->product = Product::find($productId);
  }
  public function confirmProductRemoval($id)
  {
    $this->productId = $id;
    $this->dispatchBrowserEvent('show-delete-modal-category');
  }
  public function editproduct()
  {
    $this->editproduct = true;
  }

  public function cancelproduct()
  {
    $this->editproduct = null;
  }

  public function deleteSingleRecord()
  {
    $id = $this->productId;
    $product = Product::find($id);
    $productcats = Products_categories::where('product_id', $id)->get();

    if ($productcats != NULL) {
      foreach($productcats as $productcat) {
        $productcat->delete();
      }
    }
    $productType = class_basename(get_class($product));
        $filespath = 'media/' . $productType . '/' . $product->id;
        if (File::exists($filespath)) {
            File::deleteDirectory($filespath);
        }
    $product->delete();
    return redirect()->route('products')->with('message', 'Record deleted Successfully');
  }


    public function render()
    {
        return view('livewire.show-product');
    }
}
