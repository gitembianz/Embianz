<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

  public function myorder($order_number = null)
  {
    // Retrieve the order details using the order_number
    $order = Order::where('order_number', $order_number)->first();

    // Check if order exists
    if ($order) {
      // Return a view with order details
      return view('store.myorder', compact('order'));
    } else {
      // Handle case where order is not found
      return abort(404);
    }
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
    if (($data->active == false)) {
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
