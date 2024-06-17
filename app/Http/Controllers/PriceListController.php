<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PriceList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PriceListController extends Controller
{

  public function create()
  {
    $currencies = Currency::all();
    return view('admin.add_pricelist', compact('currencies'));
  }

  public function store(Request $request)
  {
    $item = new PriceList();
    $item->name = $request->name;
    $item->currency_id = $request->currency;
    if ($request->active) {
      $item->active = true;
    } else {
      $item->active = false;
    }
    $item->createdby = Auth::user()->name;
    $item->lastmodifiedby = Auth::user()->name;
    $item->save();
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_pricelist", ["id" => $item->id]) . '">' . $item->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function show($id)
  {
    $data = PriceList::find($id);
    return view('admin.show_pricelist', compact('data'));
  }
}
