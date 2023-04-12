<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Products_categories;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    //show all products

    public function products(Request $request){

        if($request->ajax()) {
            $data= Product::query();
            return DataTables::eloquent($data)
            ->addColumn('category', function($data) {

                $testvar = $data->product_categories->first();
                if($testvar != NULL){
                    if($testvar->category !=NULL){
                    $cat = $testvar->category->name;
                    } else{$cat = "No category";}

                }else{
                    $cat = "No category";
                };

                return $cat;
            })

                ->addColumn('action', function($data){
                    $button = '<button type="button" class="view_product btn-white text-bg br-xs" name="view" onclick="event.preventDefault();location.href=\'/show_product/'.$data->id.'\'">View</button>';
                    return $button;
                })->addColumn('image', function($data){
                    $image = "not image";
                    return $image;
                })
                ->make(true);
        }
        return view('admin.products');
}

    public function add()
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.add_products', compact('categories'));
    }

    public function new(Request $request){
        //add a new product to database
        $newproduct =new Product;
        $newproduct->name=$request->product_name;
        $newproduct->product_status=$request->status;
        $newproduct->long_description=$request->long_description;
        $newproduct->short_description=$request->short_description;
        $newproduct->quantity=$request->quantity;
        $newproduct->start_date=$request->start_date;
        $newproduct->end_date=$request->end_date;
        $newproduct->seo_title=$request->seo_title;
        $newproduct->created_by=Auth::user()->name;
        $newproduct->last_modified_by=Auth::user()->name;
        $newproduct->save();

        $productcategory = new products_categories();
        $productcategory->product_id = $newproduct->id;
        $productcategory->category_id = Category::where('name', $request->category)->first()->id;
        $productcategory->save();

        return redirect()->back()->with('message','Product Added Succesfully! Please Go Back');
    }

}
