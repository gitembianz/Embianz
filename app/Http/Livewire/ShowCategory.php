<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Products_categories;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class ShowCategory extends Component
{
  public $categoryId;
  public $editcategory = null;
  public $delete = false;
  public $cat;

  public function mount($categoryId)
  {
    $this->categoryId = $categoryId;
  }
  // public function confirmItemRemoval($id)
  // {
  //   $this->categoryId = $id;
  //   $this->dispatchBrowserEvent('show-delete-modal');
  // }
  public function getCategoryProperty()
  {
    return $this->categoryQuery;
  }
  public function getCategoryQueryProperty()
  {
    return Category::find($this->categoryId);
  }
  public function editcategory()
  {
    $this->cat = [
      'name' => $this->category->name,
      'active' => $this->category->active == 1 ? true : false,
      'visible' => $this->category->store_tab == 1 ? true : false,
      'start_date' => $this->category->start_date,
      'end_date' => $this->category->end_date,
      'sequence' => $this->category->sequence,
      'short_description' => $this->category->short_description,
      'meta_description' => $this->category->meta_description,
      'long_description' => $this->category->long_description,
      'seo_title' => $this->category->seo_title,
      'seo_id' => $this->category->seo_id,
      'slider_sequence' => $this->category->slider_sequence,
      'acc_items' => $this->category->accepted_items,
      'preload' => $this->category->preload_image,
      'varprice' => $this->category->display_variant_price

    ];
    $this->editcategory = true;
  }
  public function cancelcategory()
  {
    $this->editcategory = null;
    $this->cat = [];
  }
  private function generateUniqueSeoId($name)
  {
    $seoId = Str::slug($name, '-');
    $baseSeoId = $seoId;
    $counter = 1;
    while (
      Category::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
    ) {
      $seoId = $baseSeoId . '-' . $counter;
      $counter++;
    }
    return $seoId;
  }
  public function savecategory()
  {
    $category_new = $this->cat ?? NULL;
    if (!is_null($category_new)) {
      $new = Category::find($this->categoryId);
      if (array_key_exists('name', $category_new)) {
        $new->name = $category_new['name'];
      }
      if (array_key_exists('seo_id', $category_new)) {
        if ($category_new['seo_id'] == "") {
          $new->seo_id = null;
        } elseif ($new->seo_id != $category_new['seo_id']) {
          $new->seo_id = $this->generateUniqueSeoId($category_new['seo_id']);
        }
      }
      if (array_key_exists('visible', $category_new)) {
        $new->store_tab = $category_new['visible'];
      }
      if (array_key_exists('acc_items', $category_new)) {
        $new->accepted_items = $category_new['acc_items'];
      }
      if (array_key_exists('slider_sequence', $category_new)) {
        $new->slider_sequence = $category_new['slider_sequence'];
      }
      if (array_key_exists('active', $category_new)) {
        $new->active = $category_new['active'];
      }
      if (array_key_exists('preload', $category_new)) {
        $new->preload_image = $category_new['preload'];
      }
      if (array_key_exists('varprice', $category_new)) {
        $new->display_variant_price = $category_new['varprice'];
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
      if (array_key_exists('meta_description', $category_new)) {
        $new->meta_description = $category_new['meta_description'];
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
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
    $this->cat = [];
    $this->editcategory = null;
  }
  public function confirmItemRemoval()
  {
    $this->delete = true;
  }
  public function cancelItemRemoval()
  {
    $this->delete = false;
  }
  public function deleteRecord()
  {
    $id = $this->categoryId;
    $category = Category::findOrFail($id);
    $productcats = Products_categories::where('category_id', $id)->get();
    if ($productcats != NULL) {
      foreach ($productcats as $productcat) {
        $productcat->delete();
      }
    }
    $subcategories = Subcategory::where('parent_id', $id)->orwhere('category_id', $id)->get();
    if ($subcategories != NULL) {
      foreach ($subcategories as $sub) {
        $sub->delete();
      }
    }
    $medias = $category->media()->get();
    foreach ($medias as $media) {
      $media->delete();
    }
    $productType = class_basename(get_class($category));
    $filespath = 'media/' . $productType . '/' . $category->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }
    $category->delete();
    $this->delete = false;
    return redirect()->route('category')->with('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function render()
  {
    return view('livewire.show-category', [
      'category' => $this->category
    ]);
  }
}