<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PriceList;
use Illuminate\Support\Facades\Auth;
// use App\Http\Requests\StorePriceListRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePriceListRequest;

class PriceListController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('admin.pricelists');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $currencies = Currency::all();
    return view('admin.add_pricelist', compact('currencies'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $item = new PriceList();

    $item->name = $request->name;
    $item->currency_id = $request->currency;
    $item->createdby = Auth::user()->name;
    $item->lastmodifiedby = Auth::user()->name;
    $item->save();
    return redirect()->back()->with('message', 'PriceList add succesfully!');
  }

  /**
   * Display the specified resource.
   */
  public function show(PriceList $priceList)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(PriceList $priceList)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdatePriceListRequest $request, PriceList $priceList)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(PriceList $priceList)
  {
    //
  }
}
