<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Products_categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ShowCategory extends Component
{

  public $categoryId;
  public $editcategory = null;
  public $cat = [];
  public $categories = [];

  public function mount($categoryId)
  {
    $this->categoryId = $categoryId;
  }
  public function confirmCategoryRemoval($id)
  {
    $this->categoryId = $id;
    $this->dispatchBrowserEvent('show-delete-modal-category');
  }
  public function getCategoryProperty()
  {
    return $this->categoryQuery;
  }
  public function editcategory()
  {
    $this->editcategory = true;
    $this->categories = Category::pluck('name');
  }
  public function cancelcategory()
  {
    $this->editcategory = null;
    $this->cat = [];
    $this->categories = [];
  }
  public function savecategory()
  {
    $category_new = $this->cat ?? NULL;
    if (!is_null($category_new)) {
      $new = Category::find($this->categoryId);
      if (array_key_exists('name', $category_new)) {
        $new->name = $category_new['name'];
      }
      if (array_key_exists('parrent', $category_new)) {
        $new->parrent = $category_new['parrent'];
      }
      if (array_key_exists('start_date', $category_new)) {
        $new->start_date = $category_new['start_date'];
      }
      if (array_key_exists('end_date', $category_new)) {
        $new->end_date = $category_new['end_date'];
      }
      if (array_key_exists('sequence', $category_new)) {
        $new->sequence = $category_new['sequence'];
      }
      if (array_key_exists('short_description', $category_new)) {
        $new->short_description = $category_new['short_description'];
      }
      if (array_key_exists('long_description', $category_new)) {
        $new->long_description = $category_new['long_description'];
      }
      if (array_key_exists('seo_title', $category_new)) {
        $new->seo_title = $category_new['seo_title'];
      }
      $new->lastmodifiedby = Auth::user()->name;
      $new->updated_at = now();
      $new->save();
      $this->emit('itemSaved');
      session()->flash('message', 'Category edited successfully!');
    }
    $this->cat = [];
    $this->editcategory = null;
    $this->categories = [];
  }
  public function getCategoryQueryProperty()
  {
    return Category::find($this->categoryId);
  }
  public function deleteSingleRecord()
  {
    $id = $this->categoryId;
    $category = Category::findOrFail($id);
    $productcats = Products_categories::where('category_id', $id)->get();
    if ($productcats != NULL) {
      foreach ($productcats as $productcat) {
        $productcat->delete();
      }
      $productcat->delete();
    }
    $productType = class_basename(get_class($category));
    $filespath = 'media/' . $productType . '/' . $category->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }
    $category->delete();
    return redirect()->route('category')->with('message', 'Record deleted Successfully');
  }
  public function render()
  {
    return view('livewire.show-category', [
      'category' => $this->category
    ]);
  }
}
