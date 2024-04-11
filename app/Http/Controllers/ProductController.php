<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Products_categories;



class ProductController extends Controller
{

  private function generateUniqueSeoId($name)
  {
    $seoId = Str::slug($name, '-');
    $baseSeoId = $seoId;
    $counter = 1;
    while (
      Product::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
    ) {
      $seoId = $baseSeoId . '-' . $counter;
      $counter++;
    }
    return $seoId;
  }

  public function new(Request $request)
  {
    $rules = [
      'end_date' => 'required|date|after_or_equal:today|after_or_equal:start_date',
      'sku' => 'required|unique:products',
      'ean' => 'required|unique:products',
    ];

    $messages = [
      'end_date.after_or_equal' => 'Data de încheiere a produsului trebuie să fie în viitor și după data de început.',
      'sku.unique' => 'SKU-ul trebuie să fie unic.',
      'ean.unique' => 'EAN-ul trebuie să fie unic.',

    ];
    $this->validate($request, $rules, $messages);
    if ($request->seo_id != null) {
      $seo_id = $this->generateUniqueSeoId($request->seo_id);
    } else {
      $seo_id = $this->generateUniqueSeoId($request->product_name);
    }
    $newproduct = Product::create([
      'name' => $request->product_name,
      'sku' => $request->sku,
      'ean' => $request->ean,
      'long_description' => $request->long_description,
      'short_description' => $request->short_description,
      'meta_description' => $request->meta_description,
      'quantity' => $request->quantity,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'seo_title' => $request->seo_title,
      'popularity' => $request->popularity,
      'active' => $request->has('active'),
      'is_new' => $request->has('is_new'),
      'created_by' => Auth::user()->name,
      'last_modified_by' => Auth::user()->name,
      'seo_id' => $seo_id
    ]);

    if (app('global_default_category') != 0) {
      $defaultcategory = new Products_categories();
      $defaultcategory->product_id = $newproduct->id;
      $defaultcategory->category_id = app('global_default_category');
      $defaultcategory->save();
    }


    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_product", ["id" => $newproduct->id]) . '">' . $newproduct->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function show($id)
  {
    $data = Product::find($id);
    return view('admin.show_product', compact('data'));
  }
}
