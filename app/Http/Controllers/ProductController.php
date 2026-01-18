<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\ProductReviews as ModelsProductReviews;
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

  public function create()
  {
    $brands = Brand::all();
    return view('admin.add_products', compact('brands'));
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
      'active' => $request->has('active'),
      'preorder' => $request->has('preorder'),
      'low_stock' => $request->has('low_stock'),
      'is_new' => $request->has('is_new'),
      'is_digital' => $request->has('is_digital'),
      'brand_id' => $request->brand,
      'type' => $request->type,
      'quantity' => $request->quantity,
      'quantity' => $request->quantity,
      'low_stock_quantity' => $request->low_stock_quantity,
      'popularity' => $request->popularity,
      'sku' => $request->sku,
      'ean' => $request->ean,
      'short_description' => $request->short_description,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'meta_description' => $request->meta_description,
      'comments' => $request->comments,
      'long_description' => $request->long_description,
      'seo_title' => $request->seo_title,
      'seo_id' => $seo_id,
      'google_category' => $request->google_category,
      'created_by' => Auth::user()->name,
      'last_modified_by' => Auth::user()->name,
    ]);

    if (app('global_default_category') != 0) {
      $defaultcategory = new Products_categories();
      $defaultcategory->product_id = $newproduct->id;
      $defaultcategory->category_id = app('global_default_category');
      $defaultcategory->save();
    }

    $acronims = [
      'JD',
      'AM',
      'CR',
      'LS',
      'MK',
      'PT',
      'RB',
      'SN',
      'VL',
      'XT',
      'AN',
      'BG',
      'CZ',
      'DK',
      'EV',
      'FP',
      'GH',
      'HK',
      'IL',
      'JM'
    ];

    $comments = [
      'Produs excelent, foarte mulțumit!',
      'Exact ce aveam nevoie, funcționează perfect.',
      'Calitate foarte bună și livrare rapidă.',
      'Raport calitate-preț foarte bun.',
      'A depășit așteptările mele.',
      'Produs bun, îl recomand.',
      'Sunt foarte încântat de această achiziție.',
      'Construcție solidă, se simte premium.',
      'Livrare rapidă și ambalaj de calitate.',
      'Merită cumpărat din nou.',
      'Funcționează impecabil, recomand cu încredere.',
      'Servicii excelente, produsul conform descrierii.',
      'Preț corect pentru ceea ce oferă.',
      'Foarte practic și ușor de folosit.',
      'Un produs de încredere, recomand oricui.'
    ];

    $acronim = $acronims[array_rand($acronims)];
    $slug = strtolower($acronim) . '-' . rand(1000, 9999);
    $comm = $comments[array_rand($comments)];
    ModelsProductReviews::create([
      'product_id' => $newproduct->id,
      'acronim'    => $slug,
      'score'      => rand(4, 5),
      'comment'    => $comm,
      'approved'   => true
    ]);

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
    $products = Product::whereIn('products.type', ['variant', 'standard'])
    ->leftJoin('item_media', 'products.id', '=', 'item_media.mediable_id')
    ->leftJoin('media', function ($join) {
      $join->on('item_media.media_id', '=', 'media.id')
        ->where('media.type', '=', 'original')
        ->where('media.sequence', '=', '1');
    })
    ->leftJoin('products_categories', function ($join) {
      $join->on('products.id', '=', 'products_categories.product_id')
        ->where('products_categories.primary_category', '=', 1);
    })
    ->leftJoin('categories', 'products_categories.category_id', '=', 'categories.id')
    ->leftJoin('pricelist_entries', 'products.id', '=', 'pricelist_entries.product_id')  // Join pricelist_entries for prices
      ->leftJoin('price_lists', 'pricelist_entries.pricelist_id', '=', 'price_lists.id')  // Join price_lists to get currency_id
      ->leftJoin('currencies', 'price_lists.currency_id', '=', 'currencies.id')  // Join currencies to get currency details
      ->select(
        'products.id',
        'products.name',
        'products.type',
        DB::raw('MAX(products.long_description) as long_description'),  // Aggregate long_description
        DB::raw('MAX(products.seo_id) as seo_id'),  // Aggregate seo_id
        DB::raw('MAX(products.ean) as ean'),
        DB::raw('MAX(products.preorder) as preorder'),  // Aggregate ean
        DB::raw('MAX(products.sku) as sku'),  // Aggregate sku
        DB::raw('MAX(products.brand) as brand'),  // Aggregate brand
        DB::raw('MAX(products.active) as active'),  // Aggregate active
        DB::raw('MAX(products.quantity) as quantity'),  // Aggregate quantity
        DB::raw('MAX(products.popularity) as popularity'),  // Aggregate popularity
        DB::raw('MAX(products.parent_id) as parentid'),
        DB::raw('MAX(products.short_description) as short_description'),  // Aggregate short_description
        DB::raw('MAX(products.start_date) as start_date'),  // Aggregate start_date
        DB::raw('MAX(products.end_date) as end_date'),  // Aggregate end_date
        DB::raw('MAX(products.seo_title) as seo_title'),  // Aggregate seo_title
        DB::raw('MAX(products.meta_description) as meta_description'),  // Aggregate meta_description
        DB::raw('MAX(products.is_new) as is_new'),  // Aggregate is_new
        DB::raw('MAX(products.google_category) as google_category'),  // Aggregate is_new
        DB::raw('categories.seo_title as category_seo_title'),
        DB::raw('MIN(media.path) as media_path'),  // Select first media path
        DB::raw('MIN(media.name) as media_name'),  // Select first media name
        DB::raw('MAX(pricelist_entries.value) as price'),  // Aggregate price
        DB::raw('MAX(pricelist_entries.value_no_discount) as value_no_discount'),  // Aggregate value_no_discount
        DB::raw('MAX(pricelist_entries.discount) as discount'),  // Aggregate discount
        DB::raw('MAX(pricelist_entries.value_no_vat) as price_no_vat'),  // Aggregate price_no_vat
        DB::raw('MAX(pricelist_entries.vat) as vat'),  // Aggregate VAT
        DB::raw('MAX(currencies.name) as currency_name')  // Aggregate currency name
      )
      ->groupBy('products.id', 'products.name','products.type','categories.seo_title')
      ->get();

      foreach ($products as $product) {
    $product->additional_images = DB::table('item_media')
        ->join('media', function ($join) {
            $join->on('item_media.media_id', '=', 'media.id')
                ->where('media.type', '=', 'original')
                ->whereBetween('media.sequence', [2, 8]);
        })
        ->where('item_media.mediable_id', $product->id)
        ->orderBy('media.sequence', 'asc')
        ->select('media.path', 'media.name')
        ->get();
}


    // Generate multiple CSV feeds
    $this->generateCsvFeed($products->where('active', '=', 1), 'google');
    $this->generateCsvFeed($products->where('active', '=', 1), 'facebook');
    $this->generateCsvFeed($products->where('active', '=', 1), 'tiktok');

    return redirect()->back()->with([
      'notification' => [
        'message' => 'Feeds generated successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  private function generateCsvFeed($products, $feedType)
  {
    $cheapestVariants = $this->identifyCheapestVariants($products);
    $feeds = [
      'google' => [
        'fileName' => 'google.csv',
        'headers' => ['id', 'item_group_id', 'title', 'product_type', 'description', 'link', 'mobile_link', 'image_link', 'additional_image_link', 'condition', 'price', 'sale_price', 'availability', 'brand', 'custom_label_0', 'custom_label_1', 'google_product_category'],
        'columns' => function ($product) use ($cheapestVariants) {
          $link = route('product', ['product' => $this->sanitizeData($product->seo_id ?? $product->id)]);
          $image = env('APP_URL') . "/" . $this->sanitizeData($product->media_path) . $this->sanitizeData($product->media_name);
          $image = str_replace(' ', '%20', $image);
          $additionalImages = '';
        if (isset($product->additional_images) && $product->additional_images->count() > 0) {
            $additionalImages = $product->additional_images
                ->map(function ($img) {
                    $url = env('APP_URL') . "/" . $this->sanitizeData($img->path) . $this->sanitizeData($img->name);
                    return str_replace(' ', '%20', $url);
                })
                ->implode(',');
        }
          $parentid = $this->sanitizeData($product->parentid);
          $category = $this->sanitizeData($product->short_description);
          $price = $this->sanitizeData($product->value_no_discount) . " " . $this->sanitizeData($product->currency_name);
          if ($product->value_no_discount != $product->price) {
            $sale_price = $this->sanitizeData($product->price) . " " . $this->sanitizeData($product->currency_name);
          } else {
            $sale_price = '';
          }
          $availability = ($product->preorder == 1 || $product->quantity > 0) 
            ? 'in stock' 
            : 'out of stock';
              $customLabel1 = '';
        if ($product->type === 'variant' && isset($cheapestVariants[$parentid]) && $cheapestVariants[$parentid] == $product->id) {
  $customLabel1 = 'cheapest_variant';
} elseif ($product->type === 'standard') {
  $customLabel1 = 'standard_product';
}

          return [
            $this->sanitizeData($product->id),
            $parentid,
            $this->sanitizeData($product->name),
            $category,
            strip_tags($this->sanitizeData($product->long_description)),
            $link,
            $link,
            $image,
            $additionalImages,
            'new',
            $price,
            $sale_price,
            $availability,
            $this->sanitizeData($product->brand),
            $this->sanitizeData($product->short_description),
            $customLabel1,
            $this->sanitizeData($product->google_category)
          ];
        }
      ],
      'facebook' => [
        'fileName' => 'facebook.csv',
        'headers' => ['id', 'title', 'description', 'availability', 'condition', 'price', 'sale_price','item_group_id,', 'link', 'image_link', 'brand', 'google_product_category','custom_label_1'],
        'columns' => function ($product) {
          $link = route('product', ['product' => $this->sanitizeData($product->seo_id ?? $product->id)]);
          $image = env('APP_URL') . "/" . $this->sanitizeData($product->media_path) . $this->sanitizeData($product->media_name);
          $image = str_replace(' ', '%20', $image);
          $availability = ($product->preorder == 1 || $product->quantity > 0) 
            ? 'in stock' 
            : 'out of stock';
          $category = $this->sanitizeData($product->category_seo_title ?? '');
          $parentid = $this->sanitizeData($product->parentid);
          $price = $this->sanitizeData($product->value_no_discount) . " " . $this->sanitizeData($product->currency_name);
          if ($product->value_no_discount != $product->price) {
            $sale_price = $this->sanitizeData($product->price) . " " . $this->sanitizeData($product->currency_name);
          } else {
            $sale_price = '';
          }
                        $customLabel1 = '';
        if ($product->type === 'variant' && isset($cheapestVariants[$parentid]) && $cheapestVariants[$parentid] == $product->id) {
  $customLabel1 = 'cheapest_variant';
} elseif ($product->type === 'standard') {
  $customLabel1 = 'standard_product';
}
          return [
            $this->sanitizeData($product->id),
            $this->sanitizeData($product->name),
            strip_tags($this->sanitizeData($product->long_description)),
            $availability,
            'new',
            $price,
            $sale_price,
            $parentid,
            $link,
            $image,
            $this->sanitizeData($product->brand),
            $this->sanitizeData($product->google_category),
            $customLabel1
          ];
        }
      ],
      'tiktok' => [
        'fileName' => 'tiktok.csv',
        'headers' => ['sku_id', 'title', 'description', 'availability', 'condition', 'price', 'link', 'image_link', 'brand', 'google_product_category'],
        'columns' => function ($product) {
          $link = route('product', ['product' => $this->sanitizeData($product->seo_id ?? $product->id)]);
          $image = env('APP_URL') . "/" . $this->sanitizeData($product->media_path) . $this->sanitizeData($product->media_name);
          $image = str_replace(' ', '%20', $image);

           $availability = ($product->preorder == 1 || $product->quantity > 0) 
            ? 'in stock' 
            : 'out of stock';

          $category = $this->sanitizeData($product->category_seo_title ?? '');
          return [
            $this->sanitizeData($product->id),
            $this->sanitizeData($product->name),
            strip_tags($this->sanitizeData($product->long_description)),
            $availability,
            'new',
            $this->sanitizeData($product->price) . " " . $this->sanitizeData($product->currency_name),
            $link,
            $image,
            $this->sanitizeData($product->brand),
            'Home & Garden > Plants > Seeds'
          ];
        }
      ]
    ];

    // Get feed configuration
    $feed = $feeds[$feedType];

    // Open file in write mode
    $file = fopen(public_path('feed/' . $feed['fileName']), 'w');
    fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
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
private function identifyCheapestVariants($products)
{
  $cheapestVariants = [];
  $variantsByParent = [];
  
  // Group variants by parent ID
  foreach ($products as $product) {
    if ($product->type === 'variant') {  
      $parentid = $this->sanitizeData($product->parentid);
      
      if (!isset($variantsByParent[$parentid])) {
        $variantsByParent[$parentid] = [];
      }
      
      $variantsByParent[$parentid][] = [
        'id' => $product->id,
        'price' => $product->price ?? 0
      ];
    }
  }
  

  foreach ($variantsByParent as $parentid => $variants) {

    usort($variants, function ($a, $b) {
      return $a['price'] <=> $b['price'];
    });
    

    if (!empty($variants)) {
      $cheapestVariants[$parentid] = $variants[0]['id'];
    }
  }
  
  return $cheapestVariants;
}
}
