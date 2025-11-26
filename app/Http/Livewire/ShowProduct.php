<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use App\Models\Cart_Item;
use App\Models\Product_Spec;
use App\Models\Related_Products;
use App\Models\PricelistEntries;
use App\Models\ProductCost;
use App\Models\Products_categories;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\ProductReviews as ModelsProductReviews;


class ShowProduct extends Component
{
  public $productId;
  public $editproduct = null;
  public $delete = false;
  public $prod;
  public $interimQuantity;
  public $quantitysupplier;
  public $relation = false;

  public function mount($productId)
  {
    $this->productId = $productId;
    $this->calculateInterimQuantity();
    $this->calculatequantitysupplier();
  }
  public function confirmProductRemoval($id)
  {
    $this->productId = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
    $this->delete = true;
  }
  public function cancelItemRemoval()
  {
    $this->delete = false;
    $this->relation = false;
  }
  public function editproduct()
  {
    $this->prod = [
      'product_name' => $this->product->name,
      'active' => $this->product->active == 1 ? true : false,
      'is_new' => $this->product->is_new == 1 ? true : false,
      'low_stock' => $this->product->low_stock == 1 ? true : false,
      'start_date' => $this->product->start_date,
      'end_date' => $this->product->end_date,
      'popularity' => $this->product->popularity,
      'short_description' => $this->product->short_description,
      'meta_description' => $this->product->meta_description,
      'long_description' => $this->product->long_description,
      'seo_title' => $this->product->seo_title,
      'quantity' => $this->product->quantity,
      'sku' => $this->product->sku,
      'ean' => $this->product->ean,
      'seo_id' => $this->product->seo_id,
      'type' => $this->product->type,
      'brand' => $this->product->brand,
      'comments' => $this->product->comments,
      'supplier_name' => $this->product->supplier_name,
      'low_stock_quantity' => $this->product->low_stock_quantity,
      'preorder' => $this->product->preorder == 1 ? true : false,
      'is_digital' => $this->product->is_digital == 1 ? true : false,
      'google_category' => $this->product->google_category,


    ];
    $this->editproduct = true;
  }

  public function getProductProperty()
  {
    return Product::find($this->productId);
  }
  public function calculateInterimQuantity()
  {
    $this->interimQuantity = Product::query()
      ->leftJoin('order__items as oi', 'products.id', '=', 'oi.product_id')
      ->leftJoin('orders as o', 'oi.order_id', '=', 'o.id')
      ->where('products.id', $this->productId)
      ->where('o.status_id', app('global_order_processing'))
      ->selectRaw('products.quantity + COALESCE(SUM(oi.quantity), 0) as interim_quantity')
      ->groupBy('products.id', 'products.quantity')
      ->value('interim_quantity') ?? $this->product->quantity;
  }
  public function calculateQuantitySupplier()
  {
    $this->quantitysupplier = Product::query()
      ->leftJoin('order__supplier__items as os', 'products.id', '=', 'os.product_id')
      ->leftJoin('order__suppliers as o_s', 'os.order__supplier_id', '=', 'o_s.id')
      ->where('products.id', $this->productId)
      ->where('o_s.status', '!=', 'closed') // Adjust the status check as needed
      ->selectRaw('COALESCE(SUM(os.quantity), 0) as quantity_supplier')
      ->groupBy('products.id')
      ->value('quantity_supplier') ?? 0;
  }
  private function generateUniqueSeoId($name)
  {
    $seoId = Str::slug($name, '-');
    $baseSeoId = $seoId;
    $counter = 1;
    while (
      Product::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
    ) {
      $seoId = $baseSeoId . '-' . $counter;
      $counter++;
    }
    return $seoId;
  }

  public function saveproduct()
{
    if (empty($this->prod)) {
        $this->editproduct = null;
        return;
    }

    try {

        $product = Product::find($this->productId);

        if (!$product) {
            $this->editproduct = null;
            return;
        }

        $input = $this->prod;

        $allowedFields = [
            'product_name'        => 'name',
            'supplier_name'       => 'supplier_name',
            'type'                => 'type',
            'brand'               => 'brand',
            'start_date'          => 'start_date',
            'active'              => 'active',
            'is_digital'          => 'is_digital',
            'preorder'            => 'preorder',
            'is_new'              => 'is_new',
            'low_stock'           => 'low_stock',
            'end_date'            => 'end_date',
            'quantity'            => 'quantity',
            'low_stock_quantity'  => 'low_stock_quantity',
            'short_description'   => 'short_description',
            'comments'            => 'comments',
            'meta_description'    => 'meta_description',
            'google_category'     => 'google_category',
            'popularity'          => 'popularity',
            'long_description'    => 'long_description',
            'seo_title'           => 'seo_title',
            'sku'                 => 'sku',
            'ean'                 => 'ean',
        ];

        // normal fields
        foreach ($allowedFields as $inputKey => $columnKey) {
            if (array_key_exists($inputKey, $input)) {
                $product->{$columnKey} = $input[$inputKey];
            }
        }

        // SEO logic
        if (array_key_exists('seo_id', $input)) {
            if ($input['seo_id'] === "") {
                $product->seo_id = null;
            } elseif ($product->seo_id !== $input['seo_id']) {
                $product->seo_id = $this->generateUniqueSeoId($input['seo_id']);
            }
        }

        $product->last_modified_by = Auth::user()->name;

        $product->save();

        $this->emit('itemSaved');

        session()->flash('notification', [
            'message' => 'Record edited successfully!',
            'type'    => 'success',
            'title'   => 'Success'
        ]);

        $this->prod = [];
        $this->editproduct = null;

    } catch (\Throwable $e) {

        $this->addError('database', $e->getMessage());

        session()->flash('notification', [
            'message' => 'Database error: ' . $e->getMessage(),
            'type'    => 'error',
            'title'   => 'Error'
        ]);
    }
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

  public function forcedeleteRecord()
  {
    $product = Product::find($this->productId);
    $productcarts = $product->carts_item()->get();
    if ($productcarts != NULL) {
      foreach ($productcarts as $cartitem) {
        $cart = $cartitem->cart;
        $cart->sum_amount -= $cartitem->price * $cartitem->quantity;
        $cart->quantity_amount -= $cartitem->quantity;
        $cart->final_amount -= $cartitem->price * $cartitem->quantity;
        $cart->save();
        if ($cart->final_amount <= 0 || $cart->sum_amount <= 0) {
          $cart->sum_amount = 0;
          $cart->quantity_amount = 0;
          $cart->final_amount = 0;
          $cart->save();
        }
        $cartitem->delete();
        $this->emit('cartUpdated');
      }
    }
    $productorders = $product->orders_item()->get();
    if ($productorders != NULL) {
      foreach ($productorders as $orderitem) {
        $order = $orderitem->order;
        $order->sum_amount -= $orderitem->price * $orderitem->quantity;
        $order->quantity_amount -= $orderitem->quantity;
        $order->final_amount -= $orderitem->price * $orderitem->quantity;
        $order->save();
        if ($order->final_amount <= 0 || $order->sum_amount <= 0) {
          $order->sum_amount = 0;
          $order->quantity_amount = 0;
          $order->final_amount = 0;
          $order->save();
        }
        $orderitem->delete();
        $this->emit('orderUpdated');
      }
    }
    $productordersuppliers = $product->order_suppliers()->get();
    if ($productordersuppliers != NULL) {
      foreach ($productordersuppliers as $orderitem) {
        $order = $orderitem->order;
        $order->sum_amount -= $orderitem->price * $orderitem->quantity;
        $order->final_amount -= $orderitem->price * $orderitem->quantity;
        $order->save();
        if ($order->final_amount <= 0 || $order->sum_amount <= 0) {
          $order->sum_amount = 0;
          $order->final_amount = 0;
          $order->save();
        }
        $orderitem->delete();
        $this->emit('orderUpdated');
      }
    }
    $this->deleteRecord();
  }
  public function deleteRecord()
  {
    $id = $this->productId;
    $product = Product::find($id);

    if (
      $product->carts_item()->exists() ||
      $product->orders_item()->exists() ||
      $product->order_suppliers()->exists()
    ) {
      session()->flash('notification', [
        'message' => 'This product is in use and cannot be deleted!',
        'type' => 'danger',
        'title' => 'Error'
      ]);
      $this->relation = true;
      $this->delete = false;
      return;
    }
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
    $costs = ProductCost::where('product_id', $id)->get();
    if ($costs != NULL) {
      foreach ($costs as $cost) {
        $cost->delete();
      }
    }
    $relproducts = Related_Products::where('product_id', $id)->orwhere('parent_id', $id)->get();
    if ($relproducts != NULL) {
      foreach ($relproducts as $item) {
        $item->delete();
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
    $medias = $product->media()->get();
    foreach ($medias as $media) {
      $media->delete();
    }
    $productType = class_basename(get_class($product));
    $filespath = 'media/' . $productType . '/' . $product->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }


    if ($product->type == 'parent') {
      $variants = ProductVariant::where('parent_id', $id)->get();
      if ($variants != NULL) {
        foreach ($variants as $variant) {
          $variant->delete();
        }
        $parentids = Product::where('parent_id', $id)->update(['parent_id' => NULL]);
      }
    }
    $product->delete();
    $this->delete = false;
    return redirect()->route('all_products')->with('notification', [
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
