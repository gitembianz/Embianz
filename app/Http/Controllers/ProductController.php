<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Products_categories;
use Illuminate\Support\Facades\Cache;



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
    Cache::forget('max_popularity');
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

  public function feed()
  {
      // Fetch products with type 'variant' or 'standard', their media, category, and pricing details
      $products = Product::whereIn('products.type', ['variant', 'standard'])  // Filter products by type
      ->leftJoin('item_media', function($join) {
          $join->on('products.id', '=', 'item_media.mediable_id')
               ->whereRaw('item_media.id = (SELECT MIN(im.id) FROM item_media im WHERE im.mediable_id = products.id)');
      })
      ->leftJoin('media', 'item_media.media_id', '=', 'media.id')  // Join media table to get media path
      ->leftJoin('products_categories', function ($join) {
          $join->on('products.id', '=', 'products_categories.product_id')
              ->where('products_categories.primary_category', '=', 1);  // Only get the primary category
      })
      ->leftJoin('categories', 'products_categories.category_id', '=', 'categories.id')  // Join categories table
      ->leftJoin('pricelist_entries', 'products.id', '=', 'pricelist_entries.product_id')  // Join pricelist_entries for prices
      ->select(
          'products.id',
          'products.name',
          'products.long_description',
          'products.seo_id',
          'products.ean',
          'products.brand',
          'categories.name as category_name',  // Select the primary category name
          'media.path as media_path',
          'media.name as media_name',  // Select media path if available
          'pricelist_entries.value as value'  // Price value
      )
      ->groupBy(
          'products.id',
          'products.name',
          'products.long_description',
          'products.seo_id',
          'products.ean',
          'products.brand',
          'categories.name',  // Select the primary category name
          'media.path',
          'media.name',  // Select the first media path and name
          'pricelist_entries.value'
      )
      ->get();
  
      // Define the CSV file name
      
      $fileName = 'google.csv';
      
      // Open file in write mode
      $file = fopen(public_path('feed/' . $fileName), 'w');
  
      // Add the CSV headers
      fputcsv($file, [
          'id', 'title', 'description', 'link',
          'mobile_link', 'image_link', 'condition', 'price', 'availability',
          'gtin','brand'
      ]);
  
      // Loop through the products and extract the required data
      foreach ($products as $product) {
          // Handle category fallback if null
          $link = route('product', ['product' => $product->seo_id ?? $product->id]);
      $image = env('APP_URL')."/".$product->media_path.$product->media_name;
          $category = $product->category_name ? $product->category_name : '';  // Fallback for missing category
  
          fputcsv($file, [
              $product->id,
              $product->name,
              strip_tags($product->long_description),
              $link,
              $link,
              $image,
              'New',
              $product->value,
              'in stock',
              $product->ean,
              $product->brand
              //$category,  // Insert the category name or default
             // $product->media_path ? $product->media_path : '',  // If no media path, leave blank
              
          ]);
      }
  
      // Close the file
      fclose($file);
      return;
}
}