<?php

namespace App\Http\Controllers;

use getID3;
use App\Models\Media;
use App\Models\Tabels;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\MediaLocation;
use App\Models\Products_categories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
    //show all products

    public function products(Request $request)
    {
        //Display all products
        if ($request->ajax()) {
            $data = Product::query();
            return DataTables::eloquent($data)
                ->addColumn('category', function ($data) {

                    $testvar = $data->product_categories->first();
                    if ($testvar != NULL) {
                        if ($testvar->category != NULL) {
                            $cat = $testvar->category->name;
                        } else {
                            $cat = "No category";
                        }
                    } else {
                        $cat = "No category";
                    };

                    return $cat;
                })
                //add column for action(view)
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" class="viewbtn" name="view" onclick="event.preventDefault();location.href=\'/show_product/' . $data->id . '\'">View</button>';
                    return $button;
                    //add column for images
                })->addColumn('image', function ($data) {
                    $productType = class_basename(get_class($data));
                    $type = Tabels::where('name', $productType)->first()->id;
                    $files = Media::where('item_id', $data->id)->where('tabel_id', $type)->where('location_id', '3')->first();
                    if($files){
                        $path = $files->path .$files->name;
                    } else{
                        $path = "images/resets/product.svg";
                    }


                    return $path;
                })
                ->make(true);
        }
        return view('admin.products');
    }

    public function add()
    {
        //return a view where you cand add a new product
        $categories = Category::pluck('name', 'id');
        return view('admin.add_products', compact('categories'));
    }

    public function new(Request $request)
    {
        //add a new product function
        //add product details
        $newproduct = new Product;
        $newproduct->name = $request->product_name;
        $newproduct->product_status = $request->status;
        $newproduct->long_description = $request->long_description;
        $newproduct->short_description = $request->short_description;
        $newproduct->quantity = $request->quantity;
        $newproduct->start_date = $request->start_date;
        $newproduct->end_date = $request->end_date;
        $newproduct->seo_title = $request->seo_title;
        $newproduct->created_by = Auth::user()->name;
        $newproduct->last_modified_by = Auth::user()->name;
        $newproduct->save();

        //add product's category
        $productcategory = new products_categories();
        $productcategory->product_id = $newproduct->id;
        $productcategory->category_id = Category::where('name', $request->category)->first()->id;
        $productcategory->save();

        //get location/sequences/size/files from image component
        $locations = $request->input('file_location');
        $sequences = $request->input('file_sequence');
        $size = $request->input('file_size');
        $files = $request->file('media');
        $productType = class_basename(get_class($newproduct));
        $filespath = 'media/' . $productType . '/';
        //Verifi it is a folder name 'Products' in general 'media' folders
        if (!File::exists($filespath)) {
            File::makeDirectory($filespath, 0755, true);
        }

        if ($files) {
            //verify and create a folder with product id name
            if (!File::exists($filespath . "$newproduct->id")) {
                File::makeDirectory($filespath . "$newproduct->id", 0755, true);
                $path = $filespath . "$newproduct->id" . "/";
            }
            $i = 0;
            foreach ($files as $file) {
                $media = new Media();
                //verify the type of media
                $type = $file->getClientOriginalExtension();
                if ($type === "svg") {
                    $svg = simplexml_load_file($file);
                    $attributes = $svg->attributes();
                    $width = (float) $attributes->width;
                    $height = (float) $attributes->height;
                } elseif ($type === "mp4" || $type === " ogg") {
                    $getID3 = new getID3;
                    $fileinfo = $getID3->analyze($file);
                    $width = $fileinfo['video']['resolution_x'];
                    $height = $fileinfo['video']['resolution_y'];
                } else {
                    $image = Image::make($file);
                    $width = $image->width();
                    $height = $image->height();
                }
                //save the path and the name
                $media->item_id = $newproduct->id;
                $media->path = $path;
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $media->name = $filename . '.' . $type;
                // Verify if media name exist
                if (file_exists($path . $media->name)) {
                    $i = 1;
                    while (file_exists($path . $filename . '(' . $i . ').' .$type)) {
                        $i++;
                    }
                    $media->name = $filename . '(' . $i . ').' . $type;
                }

                //store the media
                $file->move($path, $media->name);
                $media->sequence =  $sequences[$i];
                $media->tabel_id = Tabels::where('name', $productType)->first()->id;
                if ($locations[$i] != NULL) {
                    $media->location_id = MediaLocation::where('location', $locations[$i])->first()->id;
                } else {
                    $media->location_id = NULL;
                }
                $media->type = $type;
                $media->width = $width;
                $media->height =  $height;
                $media->size = $size[$i];
                $media->createdby = Auth::user()->name;
                $media->lastmodifiedby = Auth::user()->name;
                $media->save();
                $i += 1;
            }
        }

        return redirect()->back()->with([
            'message' => 'Product Added Succesfully!',
            'item_name' => $newproduct->name,
            'item_id' => $newproduct->id,
        ]);
    }
    public function show($id)
    {

        $data = Product::find($id);

        return view('admin.show_product', compact('data'));
    }
}
