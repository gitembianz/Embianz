<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    //show all products

    public function products(Request $request){

        if($request->ajax()) {
            $data= Product::query();
            return DataTables::eloquent($data)

                ->addColumn('action', function($data){
                    $button = '<button type="button" class="view_product btn-white text-bg br-xs" name="view" onclick="event.preventDefault();location.href=\'/show_product/'.$data->id.'\'">View</button>';
                    return $button;
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

}
