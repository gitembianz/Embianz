<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class CategoryController extends Controller
{


  private function generateUniqueSeoId($name)
  {
    $seoId = Str::slug($name, '-');
    $baseSeoId = $seoId;
    $counter = 1;
    while (
      Category::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
    ) {
      $seoId = $baseSeoId . '-' . $counter;
      $counter++;
    }
    return $seoId;
  }

  public function add_category(Request $request)
  {
    $rules = [
      'end_date' => 'required|date|after_or_equal:today|after_or_equal:start_date',
    ];
    $messages = [
      'end_date.after_or_equal' => 'Data de încheiere a categoriei trebuie să fie în viitor și după data de început.',
    ];
    if ($request->end_date) {

      $this->validate($request, $rules, $messages);
    }

    if ($request->seo_id != null) {
      $seo_id = $this->generateUniqueSeoId($request->seo_id);
    } else {
      $seo_id = $this->generateUniqueSeoId($request->category);
    }
    $data = new category;
    $data->name = $request->category;
    $data->long_description = $request->long_description;
    $data->short_description = $request->short_description;
    $data->meta_description = $request->meta_description;
    $data->sequence = $request->sequence;
    $data->start_date = $request->start_date;
    $data->end_date = $request->end_date;
    $data->createdby = Auth::user()->name;
    $data->lastmodifiedby = Auth::user()->name;
    $data->seo_title = $request->seo_title;
    $data->active = $request->has('active');
    $data->store_tab = $request->has('visible');
    $data->seo_id = $seo_id;
    $data->save();
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully! Click here <a href="' . route("show_category", ["id" => $data->id]) . '">' . $data->name . '</a>',
        'type' => 'success',
        'title' => 'Success'
      ]
    ]);
  }


  public function show($id)
  {
    $data = Category::find($id);
    return view('admin.show_category', compact('data'));
  }
}
