<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Products_categories;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    //show all products

    public function products(Request $request){
        $products= Product::all();

        return view('admin.products', compact('products'));
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
