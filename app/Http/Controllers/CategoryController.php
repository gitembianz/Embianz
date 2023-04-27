<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;


class CategoryController extends Controller
{

    public function category(Request $request)
    {

        if ($request->ajax()) {
            $data = Category::query();
            return DataTables::eloquent($data)
                ->addColumn('image', function ($data) {

                    $path = "no image";
                    return $path;

                })
                ->addColumn('action', function ($data) {

                    $button = '<button type="button" class="view_product btn-white text-bg br-xs" name="view" onclick="event.preventDefault();location.href=\'/show_category/' . $data->id . '\'">View</button>';
                    return $button;
                })
                ->make(true);
        }
        return view('admin.category');
    }

    public function add_category(Request $request)
    {       //add a new category to dbase
        $data = new category;
        $data->name = $request->category;
        $data->parrent = $request->parrent;
        $data->long_description = $request->long_description;
        $data->short_description = $request->short_description;
        $data->sequence = $request->sequence;
        $data->start_date = $request->start_date;
        $data->end_date = $request->end_date;
        $data->createdby = Auth::user()->name;
        $data->lastmodifiedby = Auth::user()->name;
        $data->seo_title = $request->seo_title;

        $data->save();

        //image handdler



        return redirect()->back()->with([
            'message' => 'Category Added Succesfully!',
            'category_name' => $data->name,
            'category_id' => $data->id,
        ]);
    }

    public function edit($id)
    {
        //edit category
        if (request()->ajax()) {
            $data = Category::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function new()
    {

        $categories = Category::pluck('name', 'id');
        return view('admin.add_category', compact('categories'));
    }

    public function show($id)
    {

        $data = Category::find($id);

        return view('admin.show_category', compact('data'));
    }

    public function delete(Request $request)
    {
        $id = $request->hiddenid;
        $category = category::find($id);
        $categories = category::all();

        $category->delete();
        //de verificat de ce nu trimite message to view
        return view('admin.category', compact('categories'))->with('message', 'Category Deleted Successfully!');
    }

    public function update_category(Request $request, $id)
    {
        $data = Category::find($id);
        $data->update([
            'name' => $request->category_name,
            'parrent' => $request->category_parrent,
            'long_description' => $request->category_long_description,
            'short_description' => $request->category_short_description,
            'sequence' => $request->category_sequence,
            'start_date' => $request->category_start_date,
            'end_date' => $request->category_end_date,
            'lastmodifiedby' => Auth::user()->name,
            'seo_title' =>$request->seo_title
        ]);
        return redirect()->back()->with('message', 'Category Update Successfully!');
    }
}
