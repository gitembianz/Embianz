<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ImageCategories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use DataTables;
use Validator;

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
                        } else{$path = "defaultcategory.jpg";}
                    
                    }else{
                        $path = "defaultcategory.jpg";
                    };
                   
                    return $path;
                })
                    ->addColumn('action', function($data){
                        $button = '<button type="button" class="view btn-bg text-white br-xs" name="view" onclick="event.preventDefault();location.href=\'/show_category/'.$data->id.'\'">View</button>';
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

            $image =$request->category_image;
            $images =$request->category_image_search;

            $image_search=time().'_search.'.$images->getClientOriginalExtension();
            $image_main=time().'_main.'.$image->getClientOriginalExtension();

            $request->category_image->move('categories',$image_main);
            $request->category_image_search->move('categories',$image_search);

            $imagecategory->category_id= $data->id;
            $imagecategory->img_main_path=$image_main;
            $imagecategory->img_search_path=$image_search;
            $imagecategory->img_sequence=$request->image_sequence;
            
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
            $request->category_image_main->move('categories', $image_main);
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
            'img_sequence' => $seque ? $seque : 0
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
      

    public function getImages()
{
    
    //edit category
    if(request()->ajax()){
        $data = ImageCategories::all();
        return response()->json(['result' =>$data]);
        }
}
}


