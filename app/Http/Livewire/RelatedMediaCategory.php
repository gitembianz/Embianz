<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Models\Tabels;
use Livewire\Component;
use App\Models\Category;

class RelatedMediaCategory extends Component
{
  public $categoryId;
  public $category;
  public $media;
  public $files = null;
  public $productType;
  public $type;

  public function mount($categoryId)
  {
      $this->categoryId = $categoryId;
      $this->category = Category::find($categoryId);
      $this->productType = class_basename(get_class($this->category));
      $this->type = Tabels::where('name', $this->productType)->first()->id;
      $this->files = Media::where('item_id', $categoryId)->where('tabel_id', $this->type)->with('location')->get();
  }

    public function render()
    {
        return view('livewire.related-media-category');
    }
}
