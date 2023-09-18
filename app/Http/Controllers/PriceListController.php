<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\PriceList;
use Illuminate\Support\Facades\Auth;
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
        'message' => 'Record added successfully! Click here  <a href="/show_pricelist/' . $item->id . '">' . $item->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ],
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $data = PriceList::find($id);
    return view('admin.show_pricelist', compact('data'));
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
