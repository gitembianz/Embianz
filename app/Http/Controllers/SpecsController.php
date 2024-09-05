<?php

namespace App\Http\Controllers;

use App\Models\Specs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecsController extends Controller
{

  public function store(Request $request)
  {
    $spec = new Specs();

    $spec->name = $request->name;
    $spec->um = $request->um;
    $spec->sequence = $request->sequence;
    $spec->createdby = Auth::user()->name;
    $spec->mark_as_filter = $request->has('mark_as_filter');
    $spec->lastmodifiedby = Auth::user()->name;
    $spec->save();
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_spec", ["id" => $spec->id]) . '">' . $spec->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function show($id)
  {
    $data = Specs::find($id);
    return view('admin.show_spec', compact('data'));
  }
}
