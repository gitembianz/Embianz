<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class HomeController extends Controller
{
  public function redirect()
  {
    $usertype = Auth::user()->usertype;
    if ($usertype == '1') {
      return view('admin.home');
    } else {
      return route('home');
    }
  }

  public function home()
  {

    $preload = null;
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {

      $firstcategory = app()->make('cached_categories')->filter(function ($category) {
        return $category->slider_sequence != 0;
      })->sortBy('slider_sequence')->first();
    } else {
      $firstcategory = Category::where('slider_sequence', '!=', 0)->orderby('slider_sequence')->first();
    }

    if ($firstcategory != null) {
      $media = $firstcategory->media->first(function ($mediaItem) {
        return $mediaItem->sequence == 4;
      });
      if ($media) {
        $preload = "/" . $media->path . $media->name;
      } else {
        $preload = "/images/store/default/default300.webp";
      }
    } else {
      $preload = "";
    }
  if (app()->has('global_one_product_page_system') && app('global_one_product_page_system') === 'true') {
    if (!app()->bound('one_product_ids')) {
    throw new NotFoundHttpException();
}
    $ids = app()->make('one_product_ids');

    if (count($ids) === 0) {
      if (!app()->bound('one_product_category')) {
    throw new NotFoundHttpException();
}
        $categorySlug = app()->make('one_product_category');
        return redirect()->route('products', ['categorySlug' => $categorySlug]);
    } else {
        $first = $ids[0]; // ['id' => 12, 'seo_id' => 'cool-product']
        $productRouteKey = $first['seo_id'] ?? $first['id'];

        return redirect()->route('product', ['product' => $productRouteKey]);
    }
} else {
    return view('store.home', compact('preload'));
}

  }
}
