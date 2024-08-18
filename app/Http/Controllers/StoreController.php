<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreController extends Controller
{

  public function search($slug = null)
  {
    if ($slug != null) {
      $data = $slug;
    } else {
      $data = null;
    }
    return view('store.search', compact('data'));
  }

  public function products($categorySlug = null)
  {
    $data = null;
    $can = null;
    $preload = null;
    if ($categorySlug) {
      if (is_numeric($categorySlug)) {
        $category = Category::find($categorySlug);
        if (($category->id != app('global_default_category')) && (($category == null) || ($category->active != true) || ($category->start_date > now()->format('Y-m-d')) || ($category->end_date < now()->format('Y-m-d')))) {
          throw new NotFoundHttpException();
        }
        $can = $category->id;
      } else {
        $can = $categorySlug;
        $category = Category::where('seo_id', $categorySlug)->first();
        if (($category->id != app('global_default_category')) && (($category == null) || ($category->active != true) || ($category->start_date > now()->format('Y-m-d')) || ($category->end_date < now()->format('Y-m-d')))) {
          throw new NotFoundHttpException();
        }
      }
      if ($category) {
        $data = $category;
      } else {
        throw new NotFoundHttpException();
      }
    }
    if ($categorySlug == null) {

      $category = Category::find(app('global_default_category'));
      if ($category) {
        $data = $category;
      } else {
        throw new NotFoundHttpException();
      }
    }
    $productCategory = $category->product_categories()->whereHas('product', function ($query) {
      $query->where('active', true)
            ->where('start_date', '<=', now()->format('Y-m-d'));
            ->where('end_date', '>=', now()->format('Y-m-d'));
            ->orderBy('popularity', 'desc');
  })->with([
      'product' => function ($query) {
          $query->with(['media' => function ($query) {
              $query->where('type', 'main');
          }])->orderBy('popularity', 'desc')->take(1);
      }
  ])->first();

    if ($productCategory != null && $productCategory->product->type != 'parent') {
      $preload = "/" . optional($productCategory->product->media()->first())->path . optional($productCategory->product->media()->first())->name;
    } elseif ($productCategory != null && $productCategory->product->type = 'parent' && $productCategory->product->variants->count() != 0) {
      if ($productCategory->product->variants->where('default_variant', true)->first()) {
        $element = $productCategory->product->variants->where('default_variant', true)->first()->product;
      } else {
        $element = $productCategory->product->variants->first()->product;
      }
      $preload = "/" . optional($element->media()->first())->path . optional($element->media()->first())->name;
    } else {
      $preload = '';
    }
    return view('store.products', compact('data', 'can', 'preload'));
  }

  public function show($product = null)
  {
    if (is_numeric($product)) {
      $productId = $product;
      $data = null;
    } else {
      $seoId = $product;
      $data = null;
    }

    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
      $cachedProducts = app()->make('cached_products');

      if (isset($productId)) {
        $data = $cachedProducts->firstWhere('id', $productId);
      } elseif (isset($seoId)) {
        $data = $cachedProducts->firstWhere('seo_id', $seoId);
      }

      $media = $data ? $data->media->firstWhere('type', 'full') : null;
      $preload = $media ? "/" . $media['path'] . $media['name'] : '';
    } else {
      if (isset($productId)) {
        $data = Product::with('media')->find($productId);
      } elseif (isset($seoId)) {
        $data = Product::with('media')->where('seo_id', $seoId)->first();
      }

      if ($data) {
        $media = $data->media->firstWhere('type', 'full');
        $preload = $media ? "/" . $media->path . $media->name : '';
      } else {
        $preload = '';
      }
    }

    if (!$data || $data->active != true || $data->start_date > now()->format('Y-m-d') || $data->end_date < now()->format('Y-m-d')) {
      throw new NotFoundHttpException();
    }

    return view('store.product', compact('data', 'preload'));
  }



  // payment function
  public function success()
  {
    return redirect()->route('order')->with('paymentsucces', true);
  }
  public function cancel()
  {
    return redirect()->route('order')->with('paymentcancel', true);
  }

  //  public function myorder($order_number = null)
  // {
  //   $order = Order::where('order_number', base64_decode($order_number))->first();

  //   if ($order) {
  //     return view('store.myorder', compact('order'));
  //   } else {
  //     return view('store.404');
  //   }
  // }
}
