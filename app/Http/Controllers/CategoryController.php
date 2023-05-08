<?php

namespace App\Http\Controllers;

use getID3;
use App\Models\Media;
use App\Models\Tabels;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\MediaLocation;
use App\Models\Products_categories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class CategoryController extends Controller
{

    public function category(Request $request)
    {

        if ($request->ajax()) {
            $data = Category::query();

            return DataTables::eloquent($data)
                ->addColumn('image', function ($data) {
                    $productType = class_basename(get_class($data));
                    $type = Tabels::where('name', $productType)->first()->id;
                    $files = Media::where('item_id', $data->id)->where('tabel_id', $type)->where('location_id', '3')->first();
                    if($files){
                        $path = $files->path .$files->name;
                    } else{
                        $path = "images/resets/category.svg";
                    }


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
    public function deleteMedia($id)
{
    // Find the media file by ID
    $media = Media::findOrFail($id);

    // Delete the media file from storage
    Storage::delete($media->path.$media->name);

    // Delete the media file from the database
    $media->delete();

    return response()->json(['message' => 'Media file deleted successfully']);
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

    public function media($id)
    {
        $data = Category::find($id);
        $itemType = class_basename(get_class($data));
        $type = Tabels::where('name', $itemType)->first()->id;
        $files = Media::where('item_id', $data->id)->where('tabel_id', $type)->with('location')->get();
        return view('admin.media',compact('files','data'));
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
    $products = Products_categories::where('category_id', $data->id)->get();
    $count_media = $files->count();
    $count_products = $products->count();
    return view('admin.show_category', compact('data', 'count_media', 'count_products'));

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
