<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Models\Tabels;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Livewire\WithPagination;

class RelatedMediaCategory extends Component
{
  use WithPagination;
  public $categoryId;
  public $category;
  public $showmedia = false;
  public $productType;
  public $type;
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $mediaidbeingremoved = null;
  public $columns = ['Id', 'Name', 'Media', 'Media Location', 'Sequence'];
  public $selectedColumns = [];

  public function mount($categoryId)
  {
      $this->categoryId = $categoryId;
      $this->category = Category::find($categoryId);
      $this->productType = class_basename(get_class($this->category));
      $this->type = Tabels::where('name', $this->productType)->first()->id;
      $this->selectedColumns = $this->columns;
  }

  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }

  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->files->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }

  public function updatedChecked()
  {
    $this->selectPage = false;
  }

  public function sortBy($columnName)
  {

    if ($this->orderBy === $columnName) {
      $this->orderAsc = $this->swapSortDirection();
    } else {
      $this->orderAsc = '1';
    }

    $this->orderBy = $columnName;
  }

  public function swapSortDirection()
  {

    return $this->orderAsc === '1' ? '0' : '1';
  }
   public function deleteSingleRecord(){

    $media = Media::findOrFail($this->mediaidbeingremoved);
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
    $this->checked = array_diff($this->checked, [$this->mediaidbeingremoved]);
    session()->flash('message', 'Record deleted Successfully');

   }
   public function deleteRecords()
   {

     $medias = Media::whereKey($this->checked)->get();

     foreach ($medias as $media) {
      //  $id = $media->id;
       $path = $media->path . $media->name;
       if (File::exists($path)) {
        File::delete($path);
      }

      $media->delete();
    $folder = $media->path;
    if (File::isDirectory($folder) && count(File::allFiles($folder)) === 0) {
      File::deleteDirectory($folder);
    }


     }

     $this->checked = [];
     session()->flash('message', 'Files deleted succesfuly');
   }


  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->filesQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getFilesProperty()
  {
    return $this->filesQuery->paginate($this->perPage);;
  }

  public function getFilesQueryProperty()
  {
    return Media::search($this->search)->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->where('item_id', $this->categoryId)->where('tabel_id', $this->type)->with('location');
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function confirmFileRemoval($id)
  {
    $this->mediaidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal-media');
  }

  public function confirmFilesRemovalmultiple(){

    $this->dispatchBrowserEvent('show-delete-modal-media-multiple');

  }

    public function render()
    {
        return view('livewire.related-media-category',[
          'files' => $this->files
        ]);
    }
}
