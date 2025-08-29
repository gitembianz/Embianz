<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
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
      ->sortByAsc('innerid')
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
      ->orderBy('innerid', 'ASC')
      ->first();
    if ($product) {

      $preload = $this->getPreloadImage($product, $data, $useCache);
    }

    if (app()->has('global_one_product_page_system') && app('global_one_product_page_system') === 'true') {

      $ids = app()->make('one_product_ids');

      if (count($ids) != 0) {
        $first = $ids[0];
        $productRouteKey = $first['seo_id'] ?? $first['id'];

        return redirect()->route('product', ['product' => $productRouteKey]);
      } else {
        if (!app()->bound('one_product_category')) {
          throw new NotFoundHttpException();
        }
        $id = app()->make('one_product_category');
        if ($id != null && $id != $data->id) {
          return redirect()->route('products', ['categorySlug' => $id]);
        }
      }
    }
    return view('store.products', compact('data', 'can', 'preload'));
  }

  public function blog($categorySlug = null)
  {
    $data = null;
    $can = null;
    $preload = null;
    if ($categorySlug) {
      if (is_numeric($categorySlug)) {
        $category = ArticleCategory::find($categorySlug);
      } else {
        $category = ArticleCategory::where('seo_id', $categorySlug)->first();
      }

      if (!$category || $this->isCategoryInvalid($category)) {
        throw new NotFoundHttpException();
      }

      $can = is_numeric($categorySlug) ? $category->id : $categorySlug;
      $data = $category;
    } else {
      $data = null;
    }

    return view('store.blog', compact('data', 'can', 'preload'));
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
    if (!$data->preload_image) {
      return '';
    }

    if ($product && $product->product_categories != null && $product->type != 'parent') {
      return "/" . optional($media)->path . optional($media)->name;
    }

    if ($product && $product->product_categories != null && $product->type === 'parent' && $product->variants->count() != 0) {
      $variant = $product->variants->where('default_variant', true)->first() ?? $product->variants->first();
      $element = $variant->product;
      $mediaa = $element->media->where('type', 'main')->first() ?? $element->media->first();
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

    if (
      app()->has('global_one_product_page_system') &&
      app('global_one_product_page_system') === 'true'
    ) {
      if (!app()->bound('one_product_ids')) {
        throw new NotFoundHttpException();
      }
      $ids = app()->make('one_product_ids');

      $validIds = collect($ids)->pluck('id')->all();
      $validSeoIds = collect($ids)->pluck('seo_id')->filter()->all();

      $isAllowed = (isset($productId) && in_array($productId, $validIds)) ||
        (isset($seoId) && in_array($seoId, $validSeoIds));

      if (!$isAllowed) {
        if (count($ids) === 0) {
          $categorySlug = app()->make('one_product_category') ?? app('global_default_category');
          return redirect()->route('products', ['categorySlug' => $categorySlug]);
        } else {
          $first = $ids[0];
          $productRouteKey = $first['seo_id'] ?? $first['id'];
          return redirect()->route('product', ['product' => $productRouteKey]);
        }
      }
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
