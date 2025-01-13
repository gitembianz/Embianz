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
    $useCache = app()->has('global_cache_data') && app('global_cache_data') === 'true';
    if ($categorySlug) {
      if (is_numeric($categorySlug)) {
        $category = $useCache
          ? app()->make('cached_categories')->firstWhere('id', $categorySlug)
          : Category::find($categorySlug);
      } else {
        $category = $useCache
          ? app()->make('cached_categories')->firstWhere('seo_id', $categorySlug)
          : Category::where('seo_id', $categorySlug)->first();
      }

      if (!$category || $this->isCategoryInvalid($category)) {
        throw new NotFoundHttpException();
      }

      $can = is_numeric($categorySlug) ? $category->id : $categorySlug;
      $data = $category;
    } else {
      $category = $useCache
        ? app()->make('cached_categories')->firstWhere('id', app('global_default_category'))
        : Category::find(app('global_default_category'));
      if (!$category) {
        throw new NotFoundHttpException();
      }
      $data = $category;
    }

    $product = $useCache
      ? app()->make('cached_products')->filter(function ($product) use ($category) {
        return collect($product->product_categories)->contains('category_id', $category->id);
      })->sortByDesc('popularity')
      ->sortByDesc('id')
      ->first()
      : Product::where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->whereHas('product_categories', function ($query) use ($category) {
        $query->where('category_id', $category->id);
      })
      ->with([
        'media' => function ($query) {
          $query->where('type', 'main');
        },
        'product_categories' => function ($query) use ($category) {
          $query->where('category_id', $category->id);
        }
      ])
      ->orderBy('popularity', 'desc')
      ->orderBy('id', 'desc')
      ->first();
    if ($product) {

      $preload = $this->getPreloadImage($product, $data, $useCache);
    }

    return view('store.products', compact('data', 'can', 'preload'));
  }

  private function isCategoryInvalid($category)
  {
    return $category->id != app('global_default_category') &&
      ($category->active != true ||
        $category->start_date > now()->format('Y-m-d') ||
        $category->end_date < now()->format('Y-m-d'));
  }

  private function getPreloadImage($product, $data, $useCache)
  {
    $media = $useCache ? $product->media->where('type', 'main')->first() : $product->media->first();
    if (app()->has('global_'))
      if (!$data->preload_image) {
        return '';
      }

    if ($product && $product->product_categories != null && $product->type != 'parent') {
      return "/" . optional($media)->path . optional($media)->name;
    }

    if ($product && $product->product_categories != null && $product->type == 'parent' && $product->variants->count() != 0) {
      $variant = $product->variants->where('default_variant', true)->first() ?? $product->variants->first();
      $element = $variant->product;
      $mediaa = $useCache ? $element->media->where('type', 'main')->first() : $element->media->first();
      return "/" . optional($mediaa)->path . optional($mediaa)->name;
    }

    return '';
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
