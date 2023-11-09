<?php

namespace App\Http\Controllers;

use getID3;
use App\Models\Media;
use App\Models\Tabels;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\MediaLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class ProductController extends Controller
{
  //show all products

  public function products()
  {
    return view('admin.products');
  }

  public function add()
  {
    //return a view where you cand add a new product
    return view('admin.add_products');
  }

  public function new(Request $request)
  {

    $newproduct = Product::create([
      'name' => $request->product_name,
      'sku' => $request->sku,
      'ean' => $request->ean,
      'long_description' => $request->long_description,
      'short_description' => $request->short_description,
      'quantity' => $request->quantity,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'seo_title' => $request->seo_title,
      'popularity' => $request->popularity,
      'active' => $request->has('active'),
      'created_by' => Auth::user()->name,
      'last_modified_by' => Auth::user()->name,
    ]);
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here  <a href="/show_product/' . $newproduct->id . '">' . $newproduct->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ],
    ]);
  }
  public function show($id)
  {
    $data = Product::find($id);
    return view('admin.show_product', compact('data'));
  }
}
