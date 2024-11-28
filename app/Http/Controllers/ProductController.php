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
  $products = Product::whereIn('products.type', ['variant', 'standard'])  // Filter products by type
  ->leftJoin('item_media', 'products.id', '=', 'item_media.mediable_id')  // Join item_media to get media id
  ->leftJoin('media', function($join) {
      $join->on('item_media.media_id', '=', 'media.id')
           ->where('media.type', '=', 'full');
  })
  ->leftJoin('products_categories', function ($join) {
      $join->on('products.id', '=', 'products_categories.product_id')
          ->where('products_categories.primary_category', '=', 1);  // Only get the primary category
  })
  ->leftJoin('categories', 'products_categories.category_id', '=', 'categories.id')  // Join categories table
  ->leftJoin('pricelist_entries', 'products.id', '=', 'pricelist_entries.product_id')  // Join pricelist_entries for prices
  ->leftJoin('price_lists', 'pricelist_entries.pricelist_id', '=', 'price_lists.id')  // Join price_lists to get currency_id
  ->leftJoin('currencies', 'price_lists.currency_id', '=', 'currencies.id')  // Join currencies to get currency details
  ->select(
    'products.id',
    'products.name',
    \DB::raw('MAX(products.long_description) as long_description'),  // Aggregate long_description
    \DB::raw('MAX(products.seo_id) as seo_id'),  // Aggregate seo_id
    \DB::raw('MAX(products.ean) as ean'),  // Aggregate ean
    \DB::raw('MAX(products.sku) as sku'),  // Aggregate sku
    \DB::raw('MAX(products.brand) as brand'),  // Aggregate brand
    \DB::raw('MAX(products.active) as active'),  // Aggregate active
    \DB::raw('MAX(products.quantity) as quantity'),  // Aggregate quantity
    \DB::raw('MAX(products.popularity) as popularity'),  // Aggregate popularity
    \DB::raw('MAX(products.short_description) as short_description'),  // Aggregate short_description
    \DB::raw('MAX(products.start_date) as start_date'),  // Aggregate start_date
    \DB::raw('MAX(products.end_date) as end_date'),  // Aggregate end_date
    \DB::raw('MAX(products.seo_title) as seo_title'),  // Aggregate seo_title
    \DB::raw('MAX(products.meta_description) as meta_description'),  // Aggregate meta_description
    \DB::raw('MAX(products.is_new) as is_new'),  // Aggregate is_new
    \DB::raw('MAX(products.google_category) as google_category'),  // Aggregate is_new
    \DB::raw('categories.seo_title as category_seo_title'),
    \DB::raw('MIN(media.path) as media_path'),  // Select first media path
    \DB::raw('MIN(media.name) as media_name'),  // Select first media name
    \DB::raw('MAX(pricelist_entries.value) as price'),  // Aggregate price
    \DB::raw('MAX(pricelist_entries.discount) as discount'),  // Aggregate discount
    \DB::raw('MAX(pricelist_entries.value_no_vat) as price_no_vat'),  // Aggregate price_no_vat
    \DB::raw('MAX(pricelist_entries.vat) as vat'),  // Aggregate VAT
    \DB::raw('MAX(currencies.name) as currency_name')  // Aggregate currency name
)
->groupBy('products.id', 'products.name','categories.seo_title')
->get();


    // Generate multiple CSV feeds
    $this->generateCsvFeed($products->where('active','=',1), 'google');
    $this->generateCsvFeed($products, 'salesforce');
    $this->generateCsvFeed($products->where('active','=',1), 'facebook');
}

private function generateCsvFeed($products, $feedType)
{
    // Define CSV headers and file names for each feed
    $feeds = [
        'google' => [
            'fileName' => 'google.csv',
            'headers' => ['id', 'item_group_id','title', 'product_type','description', 'link', 'mobile_link', 'image_link', 'condition', 'price', 'availability', 'brand','custom_label_0','google_product_category'],
            'columns' => function($product) {
                $link = route('product', ['product' => $this->sanitizeData($product->seo_id ?? $product->id)]);
                $image = env('APP_URL')."/".$this->sanitizeData($product->media_path).$this->sanitizeData($product->media_name);
                $image = str_replace(' ', '%20', $image);
                $category = $this->sanitizeData($product->category_seo_title ?? '');
                return [
                    $this->sanitizeData($product->id),
                    $this->sanitizeData($product->id),
                    $this->sanitizeData($product->name),
                    $category,
                    strip_tags($this->sanitizeData($product->long_description)),
                    $link,
                    $link,
                    $image,
                    'new',
                    $this->sanitizeData($product->price)." ".$this->sanitizeData($product->currency_name),
                    'in_stock',
                    $this->sanitizeData($product->brand),
                    $category,
                    $this->sanitizeData($product->google_category)
                ];
            }
        ],
        'salesforce' => [
            'fileName' => 'salesforce.csv',
             'headers' => ['id', 'item_group_id','title', 'product_type','description', 'link', 'mobile_link', 'image_link', 'condition', 'price', 'availability', 'brand','custom_label_0','google_product_category'],
            'columns' => function($product) {
                $store = $this->sanitizeData(app('global_site_url'));
                $producturl = route('product', ['product' => $this->sanitizeData($product->seo_id ?? $product->id)]);
                $image640 = env('APP_URL')."/".$this->sanitizeData($product->media_path).$this->sanitizeData($product->media_name);
                $image640 = str_replace(' ', '%20', $image640);
                $image70 = str_replace('resized640','resized70', $image640);
                $category = $this->sanitizeData($product->category_seo_title ?? '');
                return [
                    $store,
                    $this->sanitizeData($product->name),
                    $this->sanitizeData($product->id),
                    $this->sanitizeData($product->sku),
                    $this->sanitizeData($product->ean),
                    $this->sanitizeData($product->active),
                    $this->sanitizeData($product->is_new),
                    $this->sanitizeData($product->quantity),
                    $this->sanitizeData($product->popularity),
                    $this->sanitizeData($product->start_date),
                    $this->sanitizeData($product->end_date),
                    $this->sanitizeData($product->short_description),
                    $this->sanitizeData($product->long_description),
                    $this->sanitizeData($product->seo_id),
                    $this->sanitizeData($product->seo_title),
                    $producturl,
                    $image640,
                    $image70,
                    $product->price = $product->price ? floatval($product->price) : 0.00,
                    $this->sanitizeData($product->currency_name),
                    floatval($this->sanitizeData($product->vat)),
                    floatval($this->sanitizeData($product->price_no_vat)),
                    floatval($this->sanitizeData($product->discount)),
                    $category,
                    $this->sanitizeData($product->brand),
                    'Store Product'
                ];
            }
        ],
        'facebook' => [
            'fileName' => 'facebook.csv',
            'headers' => ['id','title','description', 'availability','condition', 'price', 'link', 'image_link', 'brand', 'google_product_category'],
            'columns' => function($product) {
              $link = route('product', ['product' => $this->sanitizeData($product->seo_id ?? $product->id)]);
              $image = env('APP_URL')."/".$this->sanitizeData($product->media_path).$this->sanitizeData($product->media_name);
              $image = str_replace(' ', '%20', $image);
              $category = $this->sanitizeData($product->category_seo_title ?? '');
              return [
                  $this->sanitizeData($product->id),
                  $this->sanitizeData($product->name),
                  strip_tags($this->sanitizeData($product->long_description)),
                  'in stock',
                  'new',
                  $this->sanitizeData($product->price)." ".$this->sanitizeData($product->currency_name),
                  $link,
                  $image,
                  $this->sanitizeData($product->brand),
                  $this->sanitizeData($product->google_category)
              ];
          }
        ]
    ];

    // Get feed configuration
    $feed = $feeds[$feedType];

    // Open file in write mode
    $file = fopen(public_path('feed/' . $feed['fileName']), 'w');
    fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
    fputcsv($file, $feed['headers'], ',', '"');
    foreach ($products as $product) {
      fputcsv($file, $feed['columns']($product), ',', '"');
    }

    // Close the file
    fclose($file);
}

private function sanitizeData($data)
{
    // Remove leading and trailing whitespace
    $data = trim($data);
    
    // Convert null values to an empty string
    if (is_null($data)) {
        return '';
    }

    // Check for non-string data and convert it to a string
    if (!is_string($data)) {
        $data = (string)$data;
    }

    // Sanitize special characters if needed (like removing newlines or tabs)
    $data = str_replace(["\r", "\n", "\t"], ' ', $data);
    
    return $data;
}
}