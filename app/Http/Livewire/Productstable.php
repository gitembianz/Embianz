<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Cart_Item;
use App\Models\Product_Spec;
use Livewire\WithPagination;
use App\Models\PricelistEntries;
use App\Models\Products_categories;
use App\Models\Related_Products;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Productstable extends Component
{
  use WithPagination;
  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $productidbeingremoved = null;
  public $columns;
  public $selectedColumns = [];
  public $col = false;
  public $all = false;
  public $tableName;

  public function render()
  {
    return view('livewire.productstable', [
      'products' => $this->products
    ]);
  }
  public function mount($tableName)
  {
    $this->tableName = $tableName;
    $this->columns = Schema::getColumnListing($this->tableName);

    // Exclude 'long_description' and 'short_description' columns
    $excludedColumns = ['long_description', 'short_description', 'meta_description'];
    $this->selectedColumns = array_diff($this->columns, $excludedColumns);
    $this->columns = array_diff($this->columns, $excludedColumns);
  }
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->products->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function sortBy($columnName)
  {
    if ($this->orderBy === $columnName) {
      $this->orderAsc = $this->swapSortDirection();
    } else {
      $this->orderAsc = '1';
    }
    $this->orderBy = $columnName;
  }
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->productsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getProductsProperty()
  {
    return $this->productsQuery->limit($this->loadAmount)->get();
  }
  public function getProductsQueryProperty()
  {
    return Product::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function deleteRecords()
  {
    $products = Product::whereKey($this->checked)->get();
    foreach ($products as $product) {
      $id = $product->id;
      $producttodel = Product::find($id);
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
      $relproducts = Related_Products::where('product_id', $id)->orwhere('parrent_id', $id)->get();
      if ($relproducts != NULL) {
        foreach ($relproducts as $item) {
          $item->delete();
        }
      }
      //de comentat pe viitor
      $productcarts = Cart_Item::where('product_id', $id)->get();
      if ($productcarts != NULL) {
        foreach ($productcarts as $cartitem) {
          $cart = $cartitem->cart;
          $cart->sum_amount -= $cartitem->price;
          $cart->quantity_amount -= $cartitem->quantity;
          $cart->save();
          $cartitem->delete();
          $this->emit('cartUpdated');
        }
      }
      $productswishlist = Wishlist::where('product_id', $id)->get();
      if ($productswishlist != NULL) {
        foreach ($productswishlist as $productwis) {
          $productwis->delete();
          $this->emit('wishlistUpdated');
        }
      }
      $productpricelists = PricelistEntries::where('product_id', $id)->get();
      if ($productpricelists != NULL) {
        foreach ($productpricelists as $productpricelist) {
          $productpricelist->delete();
        }
      }
      $medias = $producttodel->media()->get();
      foreach ($medias as $media) {
        $media->delete();
      }
      $productType = class_basename(get_class($producttodel));
      $filespath = 'media/' . $productType . '/' . $producttodel->id;
      if (File::exists($filespath)) {
        File::deleteDirectory($filespath);
      }
      $producttodel->delete();
    }
    $this->checked = [];
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteSingleRecord()
  {
    $id = $this->productidbeingremoved;
    $product = Product::findOrFail($id);
    $productcats = Products_categories::where('product_id', $id)->get();
    if ($productcats != NULL) {
      foreach ($productcats as $productcat) {
        $productcat->delete();
      }
    }
    $productcarts = Cart_Item::where('product_id', $id)->get();
    if ($productcarts != NULL) {
      foreach ($productcarts as $cartitem) {
        $cart = $cartitem->cart;
        $cart->sum_amount -= $cartitem->price;
        $cart->quantity_amount -= $cartitem->quantity;
        $cart->save();
        $cartitem->delete();
        $this->emit('cartUpdated');
      }
    }
    $productswishlist = Wishlist::where('product_id', $id)->get();
    if ($productswishlist != NULL) {
      foreach ($productswishlist as $productwis) {
        $productwis->delete();
        $this->emit('wishlistUpdated');
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
    $medias = $product->media()->get();
    foreach ($medias as $media) {
      $media->delete();
    }
    $productType = class_basename(get_class($product));
    $filespath = 'media/' . $productType . '/' . $product->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }
    $product->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmProductRemoval($productid)
  {
    $this->productidbeingremoved = $productid;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function confirmItemsRemoval()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
}
