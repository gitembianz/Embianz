<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{

  public function category()
  {
    return view('admin.category');
  }

  public function add_category(Request $request)
  {
    $rules = [
      'end_date' => 'required|date|after_or_equal:today|after_or_equal:start_date',
      // Add other validation rules as needed
    ];
    // Custom validation messages
    $messages = [
      'end_date.after_or_equal' => 'Data de încheiere a categoriei trebuie să fie în viitor și după data de început.',
      // Add other custom messages as needed
    ];
    $validator = $this->validate($request, $rules, $messages);
    //add a new category to dbase
    $data = new category;
    $data->name = $request->category;
    $data->long_description = $request->long_description;
    $data->short_description = $request->short_description;
    $data->sequence = $request->sequence;
    $data->start_date = $request->start_date;
    $data->end_date = $request->end_date;
    $data->createdby = Auth::user()->name;
    $data->lastmodifiedby = Auth::user()->name;
    $data->seo_title = $request->seo_title;
    // dd($request->has('active'));
    $data->active = $request->has('active');
    $data->store_tab = $request->has('visible');

    $data->save();
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here  <a href="/show_category/' . $data->id . '">' . $data->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }

  public function new()
  {
    return view('admin.add_category');
  }

  public function show($id)
  {
    $data = Category::find($id);
    return view('admin.show_category', compact('data'));
  }
}
