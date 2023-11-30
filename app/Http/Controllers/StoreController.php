<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;

class StoreController extends Controller
{
  /**
   * Display a listing of the resource.
   */
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
  public function contact()
  {
    return view('store.contact');
  }
  public function products($categoryId = null)
  {
    $category = Category::find($categoryId);
    if ($category) {
      $data = $category->id;
    } else {
      $data = "";
    }
    return view('store.products', compact('data'));
  }
  public function terms()
  {
    return view('store.terms');
  }
  public function wislist()
  {
    return view('store.wislist');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreStoreRequest $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $data = Product::find($id);
    return view('store.product', compact('data'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Store $store)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateStoreRequest $request, Store $store)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Store $store)
  {
    //
  }
}
