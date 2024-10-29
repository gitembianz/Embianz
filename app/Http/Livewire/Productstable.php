<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Cart_Item;
use App\Models\Product_Spec;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\PricelistEntries;
use App\Models\Related_Products;
use App\Models\Products_categories;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Storage;
use App\Models\ProductReviews as ModelsProductReviews;


class Productstable extends Component
{
  use WithPagination;
  use WithFileUploads;

  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $idbeingremoved = null;
  public $columns;
  public $selectedColumns = [];
  public $row = null;
  public $single = false;
  public $multiple = false;
  public $uploadcsv = false;
  public $csvFile;

  protected $rules = [
    'csvFile' => 'required|mimes:csv,txt',
  ];
  public function updatingcsvFile($value)
  {
    ini_set('max_execution_time', 300);
    ini_set('memory_limit', '512M');

    $file = fopen($value->getRealPath(), 'r');
    // Skip the header row
    $header = fgetcsv($file);
    while ($row = fgetcsv($file)) {
      $this->processRow($row);
    }

    fclose($file);
    $this->uploadcsv = false;
    session()->flash('notification', [
      'message' => 'Media added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }


  public function processRow($row)
  {
    $id = $row[0]; // id
    $mediaLink = $row[1]; // media link

    $product = Product::find($id);

    if ($product) {
      $productType = class_basename(get_class($product));
      //check for directory
      $filespath = 'media/' . $productType . '/';
      if (!File::exists($filespath)) {
        File::makeDirectory($filespath, 0755, true);
      }
      if (!File::exists($filespath . $product->id)) {
        File::makeDirectory($filespath . $product->id, 0755, true);
      }
      $path = $filespath . $product->id . "/";
      $urlComponents = parse_url($mediaLink);

      $urlWithoutParams = $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
      $mediaLink = $urlWithoutParams;
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
      $fileExtension = strtolower(pathinfo($mediaLink, PATHINFO_EXTENSION));

      if (!in_array($fileExtension, $allowedExtensions)) {
        return;
      }
      $fileContent = file_get_contents($mediaLink);
      if ($fileContent == false) {
        return;
      }
      $imageInfo = getimagesizefromstring($fileContent);
      if (app()->has('global_auto_webp') &&  app('global_auto_webp') === 'true') {
        $image = Image::make($fileContent);
        $webpContent = $image->encode('webp')->__toString();
        $fileExtension = 'webp';
        $name = strtolower(preg_replace('/\s+/', '-', $product->name));
        if (file_exists($path . $name)) {
          $j = 1;
          while (file_exists($path . $product->name . '(' . $j . ').' . $fileExtension)) {
            $j++;
          }
          $name = $product->name . '(' . $j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $webpContent);
      } else {
        $fileExtension = image_type_to_extension($imageInfo[2], false);
        $name = $product->name . '.' . $fileExtension;
        if (file_exists($path . $name)) {
          $j = 1;
          while (file_exists($path . $product->name . '(' . $j . ').' . $fileExtension)) {
            $j++;
          }
          $name = $product->name . '(' . $j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $fileContent);
      }

      $isoriginal = $product->media()->where('type', 'original')->where('sequence', '1')->first();
      if ($isoriginal) {
        $isoriginal->delete();
      }
      $media = new Media();
      $media->name = $name;
      $media->extension = $fileExtension;
      $media->width = $imageInfo[0];
      $media->height =  $imageInfo[1];
      $media->size = strlen($fileContent);
      $media->type = 'original';
      $media->sequence = 1;
      $media->path = $path;
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->save();
      $product->media()->attach($media->id);

      //Resize system
      $filePath = $path . $name;
      $file = Storage::disk('public_upload')->get($filePath);
      // Set the file content

      $ismin = $product->media()->where('type', 'min')->first();

      if (!$ismin) {
        $this->resizeImage(
          $file,
          $path,
          70,
          'min',
          $name,
          $fileExtension,
          true,
          1,
          $product
        );
      } else {
        $oldPath = $ismin->path . $ismin->name;
        if (File::exists($oldPath)) {
          File::delete($oldPath);
        }

        $resizedImage = Image::make($file)
          ->resize(70, 70, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          });

        $newPath = $ismin->path . "resized70_" . $name;
        $resizedImage->encode('webp')->save($newPath);
        $ismin->path = $ismin->path;
        $ismin->name = "resized70_" . $name;
        $ismin->sequence = 1;
        $ismin->extension = $fileExtension;
        $ismin->width = $resizedImage->width();
        $ismin->height = $resizedImage->height();
        $ismin->size = File::size($newPath);
        $ismin->lastmodifiedby = Auth::user()->name;
        $ismin->save();
      }

      $ismaim = $product->media()->where('type', 'main')->first();
      if (!$ismaim) {
        $this->resizeImage($file, $path, 300, 'main', $name, $fileExtension, true, 1, $product);
      } else {
        $oldPath = $ismaim->path . $ismaim->name;
        if (File::exists($oldPath)) {
          File::delete($oldPath);
        }

        $resizedImage = Image::make($file)
          ->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
          });

        $newPath = $ismaim->path . "resized300_" . $name;
        $resizedImage->encode('webp')->save($newPath);
        $ismaim->path = $ismaim->path;
        $ismaim->name = "resized300_" . $name;
        $ismaim->sequence = 1;
        $ismaim->extension = $fileExtension;
        $ismaim->width = $resizedImage->width();
        $ismaim->height = $resizedImage->height();
        $ismaim->size = File::size($newPath);
        $ismaim->lastmodifiedby = Auth::user()->name;
        $ismaim->save();
      }

      $isfull = $product->media()->where('type', 'full')->where('sequence', '1')->first();
      if ($isfull) {
        $isfull->delete();
      }
      $this->resizeImage($file, $path, 640, 'full', $name, $fileExtension, true, 1, $product);
      return;
    } else {
      return;
    }
  }
  private function resizeImage($file, $path, $size, $type, $name, $extension, $external, $sequence, $product)
  {
    if ($external) {
      $resizedImage = Image::make($file)
        ->resize($size, $size, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
    } else {

      $resizedImage = Image::make($file->getRealPath())
        ->resize($size, $size, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
    }

    $resizedImage->encode('webp')->save($path . "resized{$size}_" . $name);

    $resizedMedia = new Media();
    $resizedMedia->path = $path;
    $resizedMedia->name = "resized{$size}_" . $name;
    $resizedMedia->sequence = $sequence;
    $resizedMedia->extension = $extension;
    $resizedMedia->type = $type;
    $resizedMedia->width = $resizedImage->width();
    $resizedMedia->height = $resizedImage->height();
    $resizedMedia->size = File::size($path . "resized{$size}_" . $name);
    $resizedMedia->createdby = Auth::user()->name;
    $resizedMedia->lastmodifiedby = Auth::user()->name;
    $resizedMedia->save();

    $product->media()->attach($resizedMedia->id);
  }
  public function expandRow($index)
  {
    if ($this->row  === null) {
      $this->row = $index;
    } elseif ($this->row != $index) {
      $this->row = $index;
    } else {
      $this->row = null;
    }
  }

  public function render()
  {
    return view('livewire.productstable', [
      'products' => $this->products
    ]);
  }
  public function mount($tableName)
  {
    $this->columns = Schema::getColumnListing($tableName);
    $this->selectedColumns = $this->columns;
  }
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->products->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
    $this->checked = $this->productsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function getProductsProperty()
  {
    return $this->productsQuery->paginate($this->loadAmount);
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
      $relproducts = Related_Products::where('product_id', $id)->orwhere('parent_id', $id)->get();
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
      ModelsProductReviews::where('product_id', $id)->delete();

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
    $this->multiple = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteSingleRecord()
  {
    $id = $this->idbeingremoved;
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
    ModelsProductReviews::where('product_id', $id)->delete();

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
    $this->single = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemRemoval($id)
  {
    $this->idbeingremoved = $id;
    $this->single = true;
  }
  public function confirmItemsRemoval()
  {
    $this->multiple = true;
  }
  public function cancel_delete()
  {
    $this->multiple = false;
    $this->single = false;
  }

  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function ProductshuffledIds()
  {
    $products = Product::all();

    $shuffledIds = range(1, $products->count());
    shuffle($shuffledIds);

    foreach ($products as $index => $product) {
      $product->innerid = $shuffledIds[$index];
      $product->save();
    }
    session()->flash('notification', [
      'message' => 'Product ids shuffled successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function Relatedshuffleseq()
  {
    $products = Product::all();

    $shuffledIds = range(1, $products->count());
    shuffle($shuffledIds);

    foreach ($products  as $product) {
      if ($product->related_product->count() != 0) {
        $shuffledIds = range(1, $product->related_product->count());
        shuffle($shuffledIds);
        foreach ($product->related_product as $index => $related) {
          $related->sequence = $shuffledIds[$index];
          $related->save();
        }
      } else {
        continue;
      }
    }
    Cache::forget('cached_products');

    session()->flash('notification', [
      'message' => 'Related products sequence shuffled successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
