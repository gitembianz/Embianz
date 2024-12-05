<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Account;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Voucher;
use App\Models\CustomScript;
use Illuminate\Http\Request;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ProductReviews as ModelsProductReviews;
use App\Models\Promotion;
use App\Models\UserSessions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;



class AdminController extends Controller
{

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
      'cookie' => [
        'nullable',
        'integer',
        'gt:0'
      ],
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
      'name' => ' Promotion name is required',
      'end_date' => 'The end date is required.',
      'end_date.after_or_equal' => 'The end date must be in the future and after the start date.',
      'cart_amount' => 'The value must be bigger than 0',
      'cooldown_timer' => 'The value must be bigger than 0',
      'cookie' => 'The value must be bigger than 0'

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
      "cookie_time" => $request->cookie ??  30,
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
  public function show_cart($id)
  {
    $data = Cart::find($id);
    return view('admin.show_cart', compact('data'));
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
}