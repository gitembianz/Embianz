<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Status;

class StoreController extends Controller
{

  //Function for views
  public function index()
  {
    return view('store.home');
  }
  public function notfoundpage()
  {
    return view('store.404');
  }
  public function redirect()
  {
    return view('store.redirect');
  }
  public function search($slug = null)
  {
    if ($slug != null) {
      $data = $slug;
    } else {
      $data = null;
    }
    return view('store.search', compact('data'));
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

  public function myorder($order_number = null)
  {
    $order = Order::where('order_number', base64_decode($order_number))->first();

    if ($order) {
      return view('store.myorder', compact('order'));
    } else {
      return view('store.404');
    }
  }
  public function products($categorySlug = null)
  {
    $data = null;
    $can = null;
    if ($categorySlug) {
      if (is_numeric($categorySlug)) {
        $category = Category::find($categorySlug);
        if (($category->active != true) || ($category->start_date > now()->format('Y-m-d')) || ($category->end_date < now()->format('Y-m-d'))) {
          return view('store.404');
        }
        $can = $category->id;
      } else {
        $can = $categorySlug;
        $category = Category::where('seo_id', $categorySlug)->first();
        if (($category->active != true) || ($category->start_date > now()->format('Y-m-d')) || ($category->end_date < now()->format('Y-m-d'))) {
          return view('store.404');
        }
      }
      if ($category) {
        $data = $category;
      } else {
        return view('store.404');
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
    if (($data->active == false)) {
      return view('store.404');
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
