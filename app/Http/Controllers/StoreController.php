<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use App\Models\ArticleCategory;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreController extends Controller
{

  public function search($slug = null)
  {
    if ($slug != null || $slug != '') {
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


    $categories = collect(resolve(\App\Services\CategoryService::class)->getAll());


    if ($categorySlug) {
        $category = is_numeric($categorySlug)
            ? $categories->firstWhere('id', (int) $categorySlug)
            : $categories->firstWhere('slug', $categorySlug);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $can  = is_numeric($categorySlug) ? $category['id'] : $categorySlug;
        $data = $category;
    } else {
        $category = $categories->firstWhere('id', app('global_default_category'));

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $data = $category;
    }

    $categoryId = $category['id'];

    if ($useCache) {
        $product = app('cached_products')
            ->filter(fn ($product) =>
                collect($product->product_categories)
                    ->contains('category_id', $categoryId)
            )
            ->sortByDesc('popularity')
            ->sortByAsc('innerid')
            ->first();
    } else {
        $product = Product::where('active', true)
            ->whereDate('start_date', '<=', now(config('app.timezone')))
            ->whereDate('end_date', '>=', now(config('app.timezone')))
            ->whereHas('product_categories', fn ($q) =>
                $q->where('category_id', $categoryId)
            )
            ->with([
                'media' => fn ($q) => $q->where('type', 'main'),
                'product_categories' => fn ($q) => $q->where('category_id', $categoryId),
            ])
            ->orderByDesc('popularity')
            ->orderBy('innerid')
            ->first();
    }


    if ($product) {
        $preload = $this->resolvePreloadImage($product, $category, $useCache);
    }


    if (
        app()->has('global_one_product_page_system') &&
        app('global_one_product_page_system') === 'true'
    ) {

        $ids = app('one_product_ids');

        if (!empty($ids)) {
            $first = $ids[0];

            return redirect()->route('product', [
                'product' => $first['seo_id'] ?? $first['id'],
            ]);
        }

        if (!app()->bound('one_product_category')) {
            throw new NotFoundHttpException();
        }

        $redirectCategory = app('one_product_category');
        if ($redirectCategory && $redirectCategory !== $categoryId) {
            return redirect()->route('products', ['categorySlug' => $redirectCategory]);
        }
    }

    return view('store.products', compact('data', 'can', 'preload'));
}


protected function resolvePreloadImage($product, array $category, bool $useCache): string
{

    if ($useCache && !empty($category['min_image'])) {
        return $category['min_image'];
    }

    if ($product && $product->relationLoaded('media')) {
        $media = $product->media->first();
        if ($media) {
            return '/' . $media->path . $media->name;
        }
    }

    return '/images/store/default/default300.webp';
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

      if (!$category) {
        throw new NotFoundHttpException();
      }

      $can = is_numeric($categorySlug) ? $category->id : $categorySlug;
      $data = $category;
    } else {
      $data = null;
    }

    return view('store.blog', compact('data', 'can', 'preload'));
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

    if (!$data || $data->active != true || $data->start_date > now(config('app.timezone'))->format('Y-m-d') || $data->end_date < now(config('app.timezone'))->format('Y-m-d')) {
      throw new NotFoundHttpException();
    }

    return view('store.product', compact('data', 'preload'));
  }

  public function article($article = null)
  {
    if (is_numeric($article)) {
      $articleId = $article;
      $data = null;
    } else {
      $seoId = $article;
      $data = null;
    }


    if (isset($articleId)) {
      $data = Article::with('media')->find($articleId);
    } elseif (isset($seoId)) {
      $data = Article::with('media')->where('seo_id', $seoId)->first();
    }

    if ($data) {
      $media = $data->media->firstWhere('type', 'full');
      $preload = $media ? "/" . $media->path . $media->name : '';
    } else {
      $preload = '';
    }

    if (!$data || $data->active != true || $data->start_date > now(config('app.timezone'))->format('Y-m-d') || $data->end_date < now(config('app.timezone'))->format('Y-m-d')) {
      throw new NotFoundHttpException();
    }

    return view('store.article', compact('data', 'preload'));
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

}
