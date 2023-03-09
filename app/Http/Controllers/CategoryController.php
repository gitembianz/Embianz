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

            if($request->ajax()) {
                $data= Category::query()->with('image');
                return DataTables::eloquent($data)
                ->addColumn('image', function($data) {

                    $testvar = $data->image->first();
                    if($testvar != NULL){
                        if($testvar->img_search_path !=NULL){
                        $path = $testvar->img_search_path ;
                        } else{$path = "defaultcategory.svg";}

                    }else{
                        $path = "defaultcategory.svg";
                    };

                    return $path;
                })
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="view text-bg br-xs" name="view" onclick="event.preventDefault();location.href=\'/show_category/'.$data->id.'\'"><span><svg width="25px" height="25px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M772.672 575.808V448.192l70.848-70.848a370.688 370.688 0 0 0-56.512-97.664l-96.64 25.92-110.528-63.808-25.92-96.768a374.72 374.72 0 0 0-112.832 0l-25.92 96.768-110.528 63.808-96.64-25.92c-23.68 29.44-42.816 62.4-56.576 97.664l70.848 70.848v127.616l-70.848 70.848c13.76 35.264 32.832 68.16 56.576 97.664l96.64-25.92 110.528 63.808 25.92 96.768a374.72 374.72 0 0 0 112.832 0l25.92-96.768 110.528-63.808 96.64 25.92c23.68-29.44 42.816-62.4 56.512-97.664l-70.848-70.848z m39.744 254.848l-111.232-29.824-55.424 32-29.824 111.36c-37.76 10.24-77.44 15.808-118.4 15.808-41.024 0-80.768-5.504-118.464-15.808l-29.888-111.36-55.424-32-111.168 29.824A447.552 447.552 0 0 1 64 625.472L145.472 544v-64L64 398.528A447.552 447.552 0 0 1 182.592 193.28l111.168 29.824 55.424-32 29.888-111.36A448.512 448.512 0 0 1 497.472 64c41.024 0 80.768 5.504 118.464 15.808l29.824 111.36 55.424 32 111.232-29.824c56.32 55.68 97.92 126.144 118.592 205.184L849.472 480v64l81.536 81.472a447.552 447.552 0 0 1-118.592 205.184zM497.536 627.2a115.2 115.2 0 1 0 0-230.4 115.2 115.2 0 0 0 0 230.4z m0 76.8a192 192 0 1 1 0-384 192 192 0 0 1 0 384z" fill="#35424b"></path></g></svg><svg class="ml-2" width="22px" height="22px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M903.232 256l56.768 50.432L512 768 64 306.432 120.768 256 512 659.072z" fill="#35424b"></path></g></svg></span></button>';
                        return $button;
                    })
                    ->make(true);
            }
            return view('admin.category');
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

            if(isset($request->ategory_image_search)){
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

            return redirect()->back()->with('message','Category Added Succesfully!');
    }

    public function edit($id){
        //edit category
        if(request()->ajax()){
        $data = Category::findOrFail($id);
        return response()->json(['result' =>$data]);
        }
    }

    public function new(){

        $categories = Category::pluck('name');
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
        if (File::exists('categories/'.$category_image->img_main_path)) {
            File::delete('categories/'.$category_image->img_main_path);
        }
        if (File::exists('categories/'.$category_image->img_search_path)) {
            File::delete('categories/'.$category_image->img_search_path);
        }
    }
        $category->delete();

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



}


