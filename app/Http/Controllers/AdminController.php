<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Brand;
use App\Models\Order;
use App\Models\County;
use App\Models\Account;
use App\Models\Country;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Voucher;
use App\Models\Currency;
use App\Models\Exchange;
use App\Models\Promotion;
use App\Models\Static_Page;
use App\Models\CustomScript;
use App\Models\UserSessions;
use Illuminate\Http\Request;
use App\Models\Order_Supplier;
use App\Models\ProductVariant;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Models\ProductReviews as ModelsProductReviews;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUser;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Competitor;
use Illuminate\Support\Str;

class AdminController extends Controller
{


  public function store_user(Request $request)
  {
    $validated = $request->validate([
      'name'     => 'required|string|max:255',
      'email'    => 'required|email|max:255|unique:users,email',
      'password' => 'required|string|min:6',
    ]);

    $rawPassword = $validated['password'];

    $values = [
      "name"              => $validated['name'],
      "usertype"          => $request->has('usertype'),
      "email"             => $validated['email'],
      "phone"             => $request->phone,
      "adress"            => $request->adress,
      "password"          => bcrypt($rawPassword),
      "created_at"        => now(),
      "updated_at"        => now(),
    ];

    User::insert($values);

    try {
      Mail::to($validated['email'])->send(new NewUser($validated['name'], $validated['email'], $rawPassword));

      return redirect()->back()->with('notification', [
        'message' => 'User created and email sent successfully!',
        'type'    => 'success',
        'title'   => 'Success'
      ]);
    } catch (\Exception $e) {
      return redirect()->back()->with('notification', [
        'message' => 'User created, but email could not be sent.',
        'type'    => 'warning',
        'title'   => 'Email Not Sent'
      ]);
    }
  }

  private function generateUniqueSeoId($name)
  {
    $seoId = Str::slug($name, '-');
    $baseSeoId = $seoId;
    $counter = 1;
    while (
      Article::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
    ) {
      $seoId = $baseSeoId . '-' . $counter;
      $counter++;
    }
    return $seoId;
  }

  private function generateUniqueSeoIdcategory($name)
  {
    $seoId = Str::slug($name, '-');
    $baseSeoId = $seoId;
    $counter = 1;
    while (
      ArticleCategory::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
    ) {
      $seoId = $baseSeoId . '-' . $counter;
      $counter++;
    }
    return $seoId;
  }

  public function store_article(Request $request)
  {
    $rules = [
      'end_date' => 'required|date|after_or_equal:today|after_or_equal:start_date',
      'seo_id' => 'unique:articles,seo_id',
    ];

    $this->validate($request, $rules);
    if ($request->seo_id != null) {
      $seo_id = $this->generateUniqueSeoId($request->seo_id);
    } else {
      $seo_id = $this->generateUniqueSeoId($request->name);
    }
    $item = Article::create([
      'name' => $request->name,
      'active' => $request->has('active'),
      'short_description' => $request->short_description,
      'long_description' => $request->long_description,
      'meta_description' => $request->meta_description,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'seo_title' => $request->seo_title,
      'seo_id' => $seo_id,
      'created_by' => Auth::user()->name,
      'last_modified_by' => Auth::user()->name,
    ]);
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_article", ["id" => $item->id]) . '">' . $item->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function store_competitor(Request $request)
  {

    $item = Competitor::create([
      'name' => $request->name,
      'url' => $request->url,
      'created_by' => Auth::user()->name,
      'last_modified_by' => Auth::user()->name,
    ]);
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_competitor", ["id" => $item->id]) . '">' . $item->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function add_articlecategory(Request $request)
  {
    $rules = [
      'end_date' => 'required|date|after_or_equal:today|after_or_equal:start_date',
      'seo_id' => 'unique:article_categories,seo_id',
    ];

    $this->validate($request, $rules);
    if ($request->seo_id != null) {
      $seo_id = $this->generateUniqueSeoIdcategory($request->seo_id);
    } else {
      $seo_id = $this->generateUniqueSeoIdcategory($request->name);
    }
    $item = ArticleCategory::create([
      'name' => $request->name,
      'active' => $request->has('active'),
      'short_description' => $request->short_description,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'seo_title' => $request->seo_title,
      'seo_id' => $seo_id,
      'created_by' => Auth::user()->name,
      'last_modified_by' => Auth::user()->name,
    ]);
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_articlecategory", ["id" => $item->id]) . '">' . $item->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }


  public function store_page(Request $request)
  {
    if (!$request->filled('name') || !$request->filled('route') || !$request->filled('description') || !$request->filled('content') || !$request->filled('sequence')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'Please fill all imputs!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    $values = array(
      "name" => $request->name,
      "description" => $request->description,
      "route" => str_replace(' ', '-', $request->route),
      "content" => $request->content,
      "sequence" => $request->sequence,
      "active" => $request->has('active'),
      'display_in_footer' => $request->has('display_in_footer'),
      "created_by" => Auth::user()->name,
      "last_modified_by" => Auth::user()->name,
      "created_at" => now(),
      "updated_at" => now()

    );

    Static_Page::insert($values);

    Cache::forget('static_pages');
    $this->registerDynamicRoutes();

    return redirect()->back()->with('notification', [
      'message' => 'Record added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function registerDynamicRoutes()
  {
    $pages = Cache::rememberForever('static_pages', function () {
      return Static_Page::all();
    });

    foreach ($pages as $page) {
      Route::get($page->route, function () use ($page) {
        return view('store.page', ['page' => $page]);
      })->name($page->route);
    }
  }

  public function store_currency(Request $request)
  {
    $item = new Currency();
    $item->name = $request->name;
    $item->symbol = $request->symbol;
    $item->createdby = Auth::user()->name;
    $item->lastmodifiedby = Auth::user()->name;
    $item->save();
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function add_supplier()
  {
    $currencies = Currency::all();
    $exchanges = Exchange::all();
    return view('admin.add_supplier', compact('currencies', 'exchanges'));
  }

  public function corectparent()
  {
    $productVariants = ProductVariant::all();

    foreach ($productVariants as $variant) {
      Product::where('id', $variant->product_id)->update(['parent_id' => $variant->parent_id]);
    }
    return redirect()->route('home');
  }

  public function store_supplier(Request $request)
  {
    $rules = [
      'name' => 'required',
      'supplier_name' => 'required',
      'date' => 'required|date'
    ];
    $messages = [
      'name' => 'name is required',
      'date' => 'date is required'
    ];
    $this->validate(
      $request,
      $rules,
      $messages
    );

    $values = array(
      "name" => $request->name,
      'supplier_name' => $request->supplier_name,
      "date" => $request->date,
      "status" => "draft",
      'currency' => $request->currency,
      'quote_currency' => $request->exchange,
      'exchange_id' => $request->exchange,
      "created_by" => Auth::user()->name,
      "last_modified_by" => Auth::user()->name,
      "created_at" => now(),
      "updated_at" => now()
    );

    Order_Supplier::insert($values);

    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_supplier", ["id" => DB::getPdo()->lastInsertId()]) . '">' . $request->supplier_name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function store_promotion(Request $request)
  {
    $rules = [
      'name' => 'required',
      'end_date' => 'required|date|after_or_equal:start_date',
      'cart_amount' => [
        'nullable',
        'integer',
        'gt:0'

      ],
      'cooldown_timer' => [
        'nullable',
        'integer',
        'gt:0'
      ],

      'percent' => 'nullable|numeric|min:0|max:100',
      'value' => [
        'nullable',
        'gt:0'
      ]
    ];
    $messages = [
      'name' => ' Promotion name is required',
      'end_date' => 'The end date is required.',
      'end_date.after_or_equal' => 'The end date must be in the future and after the start date.',
      'cart_amount' => 'The value must be bigger than 0',
      'cooldown_timer' => 'The value must be bigger than 0',
      'cookie' => 'The value must be bigger than 0',
      'percent' => 'The value must be bigger than 0'


    ];
    $this->validate(
      $request,
      $rules,
      $messages
    );
    if ($request->filled('percent') && $request->filled('value')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'The promotion accepts either a percent or a value, not both!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    $innerid = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 15);
    if ($request->type == "counter" && $request->has('active')) {
      Promotion::where('active', true)->where('type', 'counter')->update(['active' => false]);
    }
    $values = array(
      "name" => $request->name,
      "type" => $request->type,
      "promotion_percent" => $request->percent ?? null,
      "promotion_value" => $request->value ?? null,
      "start_date" => $request->start_date,
      "end_date" => $request->end_date,
      "cooldown_timer" => $request->cooldown,
      "cart_amount" => $request->amount,
      "cookieid" => $innerid,
      "active" => $request->has('active'),
      "created_at" => now(),
      "updated_at" => now()
    );

    Promotion::insert($values);
    Cache::forget('promotions');

    return redirect()->back()->with('notification', [
      'message' => 'Record added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function show_supplier($id)
  {
    $data = Order_Supplier::find($id);
    return view('admin.show_supplier', compact('data'));
  }
  public function show_user($id)
  {
    $data = User::find($id);
    return view('admin.show_user', compact('data'));
  }
  public function show_country($id)
  {
    $data = Country::find($id);
    return view('admin.show_country', compact('data'));
  }
  public function show_county($id)
  {
    $data = County::find($id);
    return view('admin.show_county', compact('data'));
  }
  public function show_cart($id)
  {
    $data = Cart::find($id);
    return view('admin.show_cart', compact('data'));
  }
  public function show_article($id)
  {
    $data = Article::find($id);
    return view('admin.show_article', compact('data'));
  }
    public function show_articlecategory($id)
  {
    $data = ArticleCategory::find($id);
    return view('admin.show_articlecategory', compact('data'));
  }
  public function show_page($id)
  {
    $data = Static_Page::find($id);
    return view('admin.show_page', compact('data'));
  }
  public function show_competitor($id)
  {
    $data = Competitor::find($id);
    return view('admin.show_competitor', compact('data'));
  }
  public function show_session($id)
  {
    $data = UserSessions::where('sessions', $id)->first();
    if ($data) {

      return view('admin.show_session', compact('data'));
    } else {
      return redirect()->back()->with('notification', [
        'message' => 'Record not found!',
        'type' => 'warning',
        'title' => 'warning'
      ]);
    }
  }

  public function show_brand($id)
  {
    $data = Brand::find($id);
    return view('admin.show_brand', compact('data'));
  }

  function correctMediaSequence()
  {
    // Select original and full media entries with duplicate paths
    $mediaGroups = DB::table('media')
      ->select('path')
      ->groupBy('path')
      ->get();

    foreach ($mediaGroups as $mediaGroup) {
      // Get the associated media entries
      $mediaEntries = DB::table('media')
        ->where('path', $mediaGroup->path)
        ->where(
          'sequence',
          '!=',
          1
        )
        ->get();
      $index = 2;

      foreach ($mediaEntries as $item) {
        // Update the sequence and name for each media entry
        DB::table('media')
          ->where('id', $item->id)
          ->where('type', 'original')
          ->update([
            'sequence' => $index,
          ]);
        DB::table('media')
          ->where('id', $item->id)
          ->where('type', 'full')
          ->update([
            'sequence' => $index - 1,
          ]);
        $index++;
      }
    }
    return true;
  }

  function forceLogoutAndForgetUser()
  {
    Auth::logout();
    Session::forget('user');
    return Redirect::to('/');
  }

  public function show_account($id)
  {
    $data = Account::find($id);
    return view('admin.show_account', compact('data'));
  }
  public function show_script($id)
  {
    $data = CustomScript::find($id);
    return view('admin.show_script', compact('data'));
  }

  public function store_setting(Request $request)
  {
    if (!$request->filled('description') || !$request->filled('parameter') || !$request->filled('value')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'Please fill all imputs!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    $values = array(
      "parameter" => $request->parameter,
      "value" => $request->value,
      "description" => $request->description,
      "createdby" => Auth::user()->name,
      "lastmodifiedby" => Auth::user()->name,
      "created_at" => now(),
      "updated_at" => now()

    );

    Store_Settings::insert($values);
    Cache::forget('global_variables');
    return redirect()->back()->with('notification', [
      'message' => 'Record added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function store_variant(Request $request)
  {
    if (!$request->filled('name') || !$request->filled('sequence')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'Please fill all imputs!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    $values = array(
      "name" => $request->name,
      "sequence" => $request->sequence,
      "created_at" => now(),
      "updated_at" => now()
    );

    Variant::insert($values);
    return redirect()->back()->with('notification', [
      'message' => 'Record added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }


  public function store_script(Request $request)
  {
    if (!$request->filled('content')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'Please provide the specific script!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    if (!$request->filled('name')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'Please provide the script name!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }

    CustomScript::create([
      'name' => $request->name,
      'type' => $request->type,
      'content' => $request->content,
      'active' => $request->has('active')
    ]);
    Cache::forget('global_scripts');

    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully!',
        'type' => 'success',
        'title' => 'Success'
      ],
    ]);
  }


  // sadasdasdasd
  public function store_voucher(Request $request)
  {
    $rules = [
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'percent' => [
        'nullable',
        'integer',
        'between:1,100',
      ],
      'value' => [
        'nullable',
        'gt:0'
      ]
    ];
    $messages = [
      'start_date' => 'The start is required.',
      'end_date.after_or_equal' => 'The end date must be in the future and after the start date.',
      'percent' => 'The percent must be between 1-100',
      'value' => 'The value must be bigger than 0'
    ];
    $rules['percent_or_value'] = 'required_without_all:percent,value';
    $this->validate(
      $request,
      $rules,
      $messages
    );
    if ($request->filled('percent') && $request->filled('value')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'The voucher accepts either a percent or a value, not both!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    $voucher = new Voucher();
    $voucher->name = $request->name;
    $voucher->code = $request->code;
    $voucher->percent = $request->percent;
    $voucher->value = $request->value;
    $voucher->status_id = app('global_voucher_active');
    $voucher->start_date = $request->start_date;
    $voucher->end_date = $request->end_date;
    $voucher->single_use = $request->has('single_use');
    $voucher->save();
    Cache::forget('promotions');


    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully!',
        'type' => 'success',
        'title' => 'Success'
      ],
    ]);
  }

  public function show_order($id)
  {
    $data = Order::find($id);
    return view('admin.show_order', compact('data'));
  }

  // seed reviews
  public function seedreviews()
  {
    $prods = Product::where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))->get();

    foreach ($prods as $product) {
      if (!$product->reviews->first()) {
        $value = (100 / (app('max_popularity') / $product->popularity)) / 20;

        ModelsProductReviews::create([
          'product_id' => $product->id,
          'count' => 1,
          'value' => $value
        ]);
      }
    }
    return Redirect::to('/');
  }
  public function updatereviews()
  {
    $prods = Product::where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))->get();

    foreach ($prods as $product) {
      $value = (100 / (app('max_popularity') / $product->popularity)) / 20;
      ModelsProductReviews::where('product_id', $product->id)->update([
        'value' => $value,
      ]);
    }
    return Redirect::to('/');
  }
  public function updateCosts()
  {
    $products = Product::where('active', true)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->get();

    foreach ($products as $product) {
      $cartPrices = $product->carts_item()->pluck('price');
      if ($cartPrices->isNotEmpty()) {
        $averagePrice = $cartPrices->avg();
      } else {
        $averagePrice = optional($product->product_prices->first())->value;
      }

      $totalCost = 0;
      $count = 0;
      foreach ($product->order_suppliers->where('order.status', 'closed') as $orderSupplier) {
        $cost = $orderSupplier->price;
        $supplierCurrency = $orderSupplier->order->currency ?? null;
        $productCurrency = optional($product->product_prices->first())->pricelist->currency->name ?? null;

        if ($supplierCurrency && $productCurrency && $supplierCurrency !== $productCurrency) {
          $exchange = Exchange::whereHas('base_currency', function ($q) use ($supplierCurrency) {
            $q->where('name', $supplierCurrency);
          })->whereHas('quote_currency', function ($q) use ($productCurrency) {
            $q->where('name', $productCurrency);
          })->latest()->first();

          if (!$exchange) {
            $exchange = Exchange::whereHas('base_currency', function ($q) use ($productCurrency) {
              $q->where('name', $productCurrency);
            })->whereHas('quote_currency', function ($q) use ($supplierCurrency) {
              $q->where('name', $supplierCurrency);
            })->latest()->first();

            if ($exchange) {
              $cost /= $exchange->value;
            }
          } else {
            $cost *= $exchange->value;
          }
        }

        if ($cost) {
          $totalCost += $cost;
          $count++;
        }
      }


      $averageCost = $count > 0 ? ($totalCost / $count) : null;

      DB::table('product_costs')->updateOrInsert(
        ['product_id' => $product->id],
        ['price' => $averagePrice, 'cost' => $averageCost, 'date' => now(), 'created_by' => auth()->user()->name, 'last_modified_by' => auth()->user()->name, 'created_at' => now(), 'updated_at' => now()]
      );
    }

    return redirect()->route('dashboard')->with('notification', [
      'message' => 'Product cost updated!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function checkorders()
  {
    $orders = Order::with('orders')->get();

    $csvData = "Name,final_amount,real_final_amount,sum_amount,real_sum_amount,date\n";

    foreach ($orders as $order) {
      $sum_amount = 0;

      foreach ($order->orders as $item) {
        $sum_amount += $item->price * $item->quantity;
      }

      $final_amount = $sum_amount + $order->delivery_price - $order->voucher_value - $order->promotion_value;

      if (round($final_amount, 2) != round($order->final_amount, 2)) {
        $csvData .= '"' . $order->name . '",'
          . '"' . $order->final_amount . '",'
          . '"' . $final_amount . '",'
          . '"' . $order->sum_amount . '",'
          . '"' . $sum_amount . '",'
          . '"' . $order->created_at->format('Y-m-d H:i:s') . '"' . "\n";
      }
    }

    session()->flash('notification', [
      'message' => 'Records downloaded successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);

    return Response::streamDownload(function () use ($csvData) {
      echo $csvData;
    }, 'orders_products.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
  }

  public function getavgvalues()
  {
    $orders = Order::with('orders.product.costs')->get();
    foreach ($orders as $order) {
      $cost = 0;
      foreach ($order->orders as $item) {
        if ($item->product->costs->isEmpty()) {
          $cost = 0;
          break;
        }
        if (!$item->product->costs->last()->cost) {
          $possiblecost = $item->product->costs->where('cost', '!=', null)->last();
          if ($possiblecost) {
            $cost += $possiblecost->cost * $item->quantity;
          } else {
            $cost = 0;
            break;
          }
        } else {
          $cost += optional($item->product->costs->last())->cost  * $item->quantity;
        }
      }
      if ($cost > 0) {
        $order->update(['avg_cost' => $cost]);
      }
    }
    return redirect()->route('orders')->with('notification', [
      'message' => 'Average cost updated!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
