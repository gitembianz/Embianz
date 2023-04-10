<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ImageCategories;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;


class CategoryController extends Controller
{

    public function category(Request $request){

        $categories = Category::all();
            return view('admin.category', compact('categories'));
    }

    public function add_category(Request $request)
    {       //add a new category to dbase
            $data=new category;
            $imagecategory= new imagecategories;
            $data->name=$request->category;
            $data->parrent=$request->parrent;
            $data->long_description=$request->long_description;
            $data->short_description=$request->short_description;
            $data->sequence=$request->sequence;
            $data->start_date=$request->start_date;
            $data->end_date=$request->end_date;
            $data->createdby=Auth::user()->name;
            $data->lastmodifiedby=Auth::user()->name;
            $data->save();

            //save category image
            //Check the input or browse
            if(isset($request->category_image)){
                $image =$request->category_image;
                $image_main = time().'_main.'.$image->getClientOriginalExtension();
                $request->category_image->move('categories',$image_main);

            } elseif(isset($request->select_image_main_hidden)) {
                $image_main =$request->select_image_main_hidden;
            } else{
                $image_main = NULL;
            }

            if ($image_main) {
                $imagecategory->img_main_path=$image_main;
            }

            if(isset($request->category_image_search)){
                $images =$request->category_image_search;
                $image_search=time().'_search.'.$images->getClientOriginalExtension();
                $request->category_image_search->move('categories',$image_search);
            } elseif(isset($request->select_image_search_hidden)) {
                $image_search =$request->select_image_search_hidden;
            } else{
                $image_search = NULL;
            }

            if ($image_search) {

                $imagecategory->img_search_path=$image_search;
            }

            $seq= $request->image_sequence;
            if($seq){
                $imagecategory->img_sequence=$request->image_sequence;
            }

            $imagecategory->category_id= $data->id;
            $imagecategory->save();

            return redirect()->back()->with('message','Category Added Succesfully! Please go back!');
    }

    public function edit($id){
        //edit category
        if(request()->ajax()){
        $data = Category::findOrFail($id);
        return response()->json(['result' =>$data]);
        }
    }

    public function new(){

        $categories = Category::pluck('name', 'id');
        return view('admin.add_category', compact('categories'));
    }

    public function show($id){

        $data = Category::find($id);

        return view('admin.show_category', compact('data'));

    }

    public function delete(Request $request){
        $id=$request->hiddenid;
        $category=category::find($id);
        $category_image=ImageCategories::where('category_id', $category->id)->first();
        if($category_image){
            // Check if there are other ImageCategories with the same img_main_path or img_search_path
            $other_images = ImageCategories::where('img_main_path', $category_image->img_main_path)
                                           ->orWhere('img_search_path', $category_image->img_search_path)
                                           ->get();
            if(count($other_images) == 0){
                // No other ImageCategories with the same img_main_path or img_search_path, delete the files
                if (File::exists('categories/'.$category_image->img_main_path)) {
                    File::delete('categories/'.$category_image->img_main_path);
                }
                if (File::exists('categories/'.$category_image->img_search_path)) {
                    File::delete('categories/'.$category_image->img_search_path);
                }
            }
        }
        $category->delete();
        //de verificat de ce nu trimite message to view
        return view('admin.category')->with('message', 'Category Deleted Successfully!');
    }

    public function update_category(Request $request, $id) {
        $data = Category::find($id);
        $data->update([
          'name' => $request->category_name,
          'parrent' => $request->category_parrent,
          'long_description' => $request->category_long_description,
          'short_description' => $request->category_short_description,
          'sequence' => $request->category_sequence,
          'start_date' => $request->category_start_date,
          'end_date' => $request->category_end_date,
          'lastmodifiedby' => Auth::user()->name
        ]);

        $imagem = $request->category_image_main;
        $images = $request->category_image_search;
        $seque = $request->category_image_sequence;

        if ($data->image->first() != NULL) {
          $imageData = [
            'category_id' => $data->id
          ];
          if ($imagem) {
            $image_main = time() . '_main.' . $imagem->getClientOriginalExtension();
            $imagem->move('categories', $image_main);
            $imageData['img_main_path'] = $image_main;
          }
          if ($images) {
            $image_search = time() . '_search.' . $images->getClientOriginalExtension();
            $request->category_image_search->move('categories', $image_search);
            $imageData['img_search_path'] = $image_search;
          }
          if ($seque) {
            $imageData['img_sequence'] = $seque;
          }
          $data->image->first()->update($imageData);
            } else {
                $imageData = [
                'category_id' => $data->id,
                 ];
            if ($imagem) {
            $image_main = time() . '_main.' . $imagem->getClientOriginalExtension();
            $request->category_image_main->move('categories', $image_main);
            $imageData['img_main_path'] = $image_main;
          }
          if ($images) {
            $image_search = time() . '_main.' . $images->getClientOriginalExtension();
            $request->category_image_search->move('categories', $image_search);
            $imageData['img_search_path'] = $image_search;
          }
          imagecategories::create($imageData);
        }
        return redirect()->back()->with('message', 'Category Update Successfully!');
      }

      public function browse()
    {
        $images = [];
        $files = File::files(public_path().'/categories');
        foreach ($files as $file) {
            $images[] = [
            'path' => $file->getPathName(),
            'filename' => $file->getFilename()
             ];
         }
         return response()->json($images);
    }

    public function selectli($id){
        //edit category
        if(request()->ajax()){
        $data = Category::findOrFail($id);
        return response()->json(['result' =>$data]);
        }
    }



}
