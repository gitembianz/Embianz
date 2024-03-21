<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{

  //Function for views
  public function index()
  {
    return view('store.home');
  }
  public function cart()
  {
    return view('store.cart');
  }
  public function order()
  {
    return view('store.order');
  }
  public function complete()
  {
    return view('store.complete');
  }
  public function faq()
  {
    return view('store.faq');
  }
  public function cookie()
  {
    return view('store.cookie');
  }
  public function privacy()
  {
    return view('store.privacy');
  }
  public function about()
  {
    return view('store.about');
  }
  public function confirm()
  {
    return view('store.confirm');
  }
  public function contact()
  {
    return view('store.contact');
  }
  public function products($categorySlug = null)
  {
    $data = null;
    $can = null;

    if ($categorySlug) {
      if (is_numeric($categorySlug)) {
        $category = Category::find($categorySlug);
        $can = $category->id;
      } else {
        $can = $categorySlug;
        $category = Category::where('seo_id', $categorySlug)->first();
      }
      if ($category) {
        $data = $category;
      }
    }

    return view('store.products', compact('data', 'can'));
  }
  public function terms()
  {
    return view('store.terms');
  }
  public function wislist()
  {
    return view('store.wislist');
  }
  public function show($product = null)
  {
    if (is_numeric($product)) {
      $data = Product::find($product);
    } else {

      $data = Product::where('seo_id', $product)->first();
    }
    if ($data->active == false || $data->start_date >= now()->format('Y-m-d') || $data->end_date <= now()->format('Y-m-d')) {
      abort(404);
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
}
