<?php

namespace App\Http\Controllers;

use App\Models\Specs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateSpecsRequest;

class SpecsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('admin.specs');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.add_spec');
  }


  /**
   * Store a newly created resource in storage.
   */
  // public function store(StoreSpecsRequest $request)
  // {
  //     //
  // }
  public function store(Request $request)
  {
    $spec = new Specs();

    $spec->name = $request->name;
    $spec->um = $request->um;
    $spec->createdby = Auth::user()->name;
    $spec->lastmodifiedby = Auth::user()->name;
    $spec->save();
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here  <a href="/show_spec/' . $spec->id . '">' . $spec->name . '</a>',
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
    $data = Specs::find($id);
    return view('admin.show_spec', compact('data'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Specs $specs)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateSpecsRequest $request, Specs $specs)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Specs $specs)
  {
    //
  }
}
