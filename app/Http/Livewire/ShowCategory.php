<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Products_categories;
use Illuminate\Support\Facades\File;

class ShowCategory extends Component
{

  public $categoryId;
  public $category;

  public function mount($categoryId)
  {
      $this->categoryId = $categoryId;
      $this->category = Category::find($categoryId);
  }
  public function confirmCategoryRemoval($id)
  {
    $this->categoryId = $id;
    $this->dispatchBrowserEvent('show-delete-modal-category');
  }
  public function deleteSingleRecord()
  {
    $id = $this->categoryId;
    $category = Category::findOrFail($id);
    $productcat = Products_categories::where('category_id', $id)->first();

    if ($productcat != NULL) {
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
      return view('livewire.show-category');
  }
}
