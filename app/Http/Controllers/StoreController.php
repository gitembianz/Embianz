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
    return view('store.products', compact('data', 'can'));
  }

  public function show($product = null)
  {
    if (is_numeric($product)) {
      $data = Product::find($product);
    } else {

      $data = Product::where('seo_id', $product)->first();
    }
    if (($data == null) || ($data->active != true) || ($data->start_date > now()->format('Y-m-d')) || ($data->end_date < now()->format('Y-m-d'))) {
      throw new NotFoundHttpException();
    }
    return view('store.product', ['data' => $data]);
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
