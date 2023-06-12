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


class CategoryController extends Controller
{

    public function category()
    {
        return view('admin.category');
    }
    public function deleteMedia($id)
{
    // Find the media file by ID
    $media = Media::findOrFail($id);
    $path = $media->path . $media->name;

    if (File::exists($path)) {
        File::delete($path);
    }

    // Delete the media file from the database
    $media->delete();
    $folder = $media->path;
    if (File::isDirectory($folder) && count(File::allFiles($folder)) === 0) {
        File::deleteDirectory($folder);
    }

    return response()->json(['message' => $path]);
}

public function deleteProduct($id)
{
    // Find the media file by ID
    $product = Products_categories::where('product_id', $id)->first();
    $product->delete();


    return response()->json(['message' => 'Product deleted succesfully']);
}

public function deleteSelectedProducts(Request $request)
{
    $productIds = $request->input('productIds');
    $productIdsArray = explode(',', $productIds);
    $count = count($productIdsArray);

    if ($count > 0) {
        for ($i = 0; $i < $count; $i++) {
            $id = $productIdsArray[$i];
            $product = Product::find($id);
            $productcat = Products_categories::where('product_id', $id)->first();
            $productcat->delete();
            $product->delete();
        }
        return redirect()->back()->with('message', 'Products Deleted Successfully!');
    } else {
        return redirect()->back()->with('message', 'No products selected for deletion.');
    }
}

public function addSelectedProducts(Request $request, $id)
{
    $productIds = $request->input('productIdsadd');
    $productIds = json_decode($productIds);
    $count = count($productIds);
    $productIdsArray = $productIds;

    if ($count > 0) {
        for ($i = 0; $i < $count; $i++) {
            $prodid = $productIdsArray[$i];
            $productcategory = new products_categories();
            $productcategory->product_id = $prodid;
            $productcategory->category_id = Category::where('id', $id)->first()->id;
            $productcategory->save();
        }
        return redirect()->back()->with('message', 'Products Added Successfully!');
    } else {
        return redirect()->back()->with('message', 'No products selected!');
    }
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
        //get location/sequences/size/files from image component
        $locations = $request->input('file_location');
        $sequences = $request->input('file_sequence');
        $size = $request->input('file_size');
        $files = $request->file('media');
        $productType = class_basename(get_class($data));
        $filespath = 'media/' . $productType . '/';
        if (!File::exists($filespath)) {
            File::makeDirectory($filespath, 0755, true);
        }

        if ($files) {
            //verify and create a folder with product id name
            if (!File::exists($filespath . "$data->id")) {
                File::makeDirectory($filespath . "$data->id", 0755, true);

            }
            $path = $filespath . "$data->id" . "/";
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
                $media->item_id = $data->id;
                $media->path = $path;
                $media->name = $file->getClientOriginalName();
                //store the media
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
            'message' => 'Category Added Succesfully!',
            'item_name' => $data->name,
            'item_id' => $data->id,
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


    public function update(Request $request, $id)
{
    // Retrieve the file with the given ID
    $file = Media::findOrFail($id);

    // Update the file's properties based on the request data
    $file->location_id = MediaLocation::where('location', $request->input('location'))->first()->id;
    $file->sequence = $request->input('sequence');


    // Save the changes to the database
    $file->save();

    // Return a JSON response indicating success
    return response()->json(['success' => true]);
}


    public function show($id)
{
    $data = Category::find($id);
    $productType = class_basename(get_class($data));
    $type = Tabels::where('name', $productType)->first()->id;
    $files = Media::where('item_id', $data->id)->where('tabel_id', $type)->with('location')->get();
    $products = Products_categories::where('category_id', $data->id)->with('product')->get();
    $count_media = $files->count();
    $count_products = $products->count();
    return view('admin.show_category', compact('data', 'count_media','files', 'count_products', 'products'));

}

    public function delete(Request $request)
    {
        $id = $request->hiddenid;
        $category = category::find($id);
        $categories = category::all();
        $productType = class_basename(get_class($category));
        $filespath = 'media/' . $productType . '/' . $category->id;
        if (File::exists($filespath)) {
            File::deleteDirectory($filespath);
        }
        $products = Products_categories::where('category_id', $category->id)->get();
        foreach($products as $product){
            $product->delete();
        }
        $category->delete();


        return view('admin.category', compact('categories'))->with('message', 'Category Deleted Successfully!');
    }
    public function getAllProducts()
{
    $products = Product::all();

    return response()->json(['products' => $products]);
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
    public function add_media(Request $request, $id)
    {
        $data = Category::find($id);
        $locations = $request->input('file_location');
        $sequences = $request->input('file_sequence');
        $size = $request->input('file_size');
        $files = $request->file('media');
        $productType = class_basename(get_class($data));
        $filespath = 'media/' . $productType . '/';
        if (!File::exists($filespath)) {
            File::makeDirectory($filespath, 0755, true);
        }

        //verify and create a folder with product id name
        if (!File::exists($filespath . "$data->id")) {
            File::makeDirectory($filespath . "$data->id", 0755, true);

        }
        $path = $filespath . "$data->id" . "/";
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
            $media->item_id = $data->id;
            $media->path = $path;
            $media->name = $file->getClientOriginalName();
            //store the media
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

        return redirect()->back()->with('message', 'Media Update Successfully!');
    }
}
