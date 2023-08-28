<?php

namespace App\Http\Controllers;

// use getID3;
// use App\Models\Media;
use App\Models\Category;
use Illuminate\Http\Request;
// use App\Models\MediaLocation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\File;
// use Intervention\Image\Facades\Image;


class CategoryController extends Controller
{

  public function category()
  {
    return view('admin.category');
  }

  public function add_category(Request $request)
  {       //add a new category to dbase
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
    if ($request->active) {
      $data->active = true;
    } else {
      $data->active = false;
    }
    if ($request->visible) {
      $data->store_tab = true;
    } else {
      $data->store_tab = false;
    }

    $data->save();

    //image handdler
    //get location/sequences/size/files from image component
    // $locations = $request->input('file_location');
    // $sequences = $request->input('file_sequence');
    // $size = $request->input('file_size');
    // $files = $request->file('media');
    // $productType = class_basename(get_class($data));
    // $filespath = 'media/' . $productType . '/';
    // if (!File::exists($filespath)) {
    //   File::makeDirectory($filespath, 0755, true);
    // }

    // if ($files) {
    //   //verify and create a folder with product id name
    //   if (!File::exists($filespath . "$data->id")) {
    //     File::makeDirectory($filespath . "$data->id", 0755, true);
    //   }
    //   $path = $filespath . "$data->id" . "/";
    //   $i = 0;
    //   foreach ($files as $file) {
    //     $media = new Media();
    //     //verify the type of media
    //     $type = $file->getClientOriginalExtension();
    //     if ($type === "svg") {
    //       $svg = simplexml_load_file($file);
    //       $attributes = $svg->attributes();
    //       $width = (float) $attributes->width;
    //       $height = (float) $attributes->height;
    //     } elseif ($type === "mp4" || $type === " ogg") {
    //       $getID3 = new getID3;
    //       $fileinfo = $getID3->analyze($file);
    //       $width = $fileinfo['video']['resolution_x'];
    //       $height = $fileinfo['video']['resolution_y'];
    //     } else {
    //       $image = Image::make($file);
    //       $width = $image->width();
    //       $height = $image->height();
    //     }
    //     //save the path and the name
    //     $media->path = $path;
    //     $media->name = $file->getClientOriginalName();
    //     //store the media
    //     $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //     $media->name = $filename . '.' . $type;
    //     // Verify if media name exist
    //     if (file_exists($path . $media->name)) {
    //       $i = 1;
    //       while (file_exists($path . $filename . '(' . $i . ').' . $type)) {
    //         $i++;
    //       }
    //       $media->name = $filename . '(' . $i . ').' . $type;
    //     }
    //     $file->move($path, $media->name);
    //     $media->sequence =  $sequences[$i];
    //     if ($locations[$i] != NULL) {
    //       $media->location_id = MediaLocation::where('location', $locations[$i])->first()->id;
    //     } else {
    //       $media->location_id = NULL;
    //     }
    //     $media->type = $type;
    //     $media->width = $width;
    //     $media->height =  $height;
    //     $media->size = $size[$i];
    //     $media->createdby = Auth::user()->name;
    //     $media->lastmodifiedby = Auth::user()->name;
    //     $media->external = false;
    //     $media->save();
    //     $data->media()->attach($media->id);
    //     $i += 1;
    //   }
    // }

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
