<?php

namespace App\Http\Livewire;

use getID3;
use App\Models\Media;
use App\Models\Tabels;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use App\Exports\MediasExport;
use App\Models\MediaLocation;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class RelatedMediaCategory extends Component
{
  use WithFileUploads;
  use WithPagination;
  public $categoryId;
  public $category;
  public $showmedia = false;
  public $productType;
  public $type;
  public $medias = [];
  public $filess = [];
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $mediaidbeingremoved = null;
  public $columns = ['Id', 'Media', 'Media Location', 'Sequence'];
  public $selectedColumns = [];
  public $locations;
  public $file_sequences = ['0'];
  public $file_locations = ['1'];
  public $file_link = [];
  public $file_name = [];
  public $col = false;
  public $all = false;
  public $hasResults;
  public $editedMediaIndex = null;
  public $i;
  public $j;
  public $row = 1;
  public $externalmedia = false;


  public function mount($categoryId)
  {
    $this->categoryId = $categoryId;
    $this->category = Category::find($categoryId);
    $this->productType = class_basename(get_class($this->category));
    $this->type = Tabels::where('name', $this->productType)->first()->id;
    $this->selectedColumns = $this->columns;
    $this->locations = MediaLocation::all();
    $this->file_locations[] = '1';
    $this->i = null;
    $this->j = null;
  }
  public function uploadmedia()
  {
    $this->showmedia = true;
    $this->dispatchBrowserEvent('media');
  }
  public function clearall()
  {
    $this->row = 0;
    $this->externalmedia = false;
    $this->file_sequences = [];
    $this->file_link = [];
    $this->file_name = [];
  }
  public function external()
  {
    $this->row = 1;
    $this->externalmedia = true;
  }
  public function plus()
  {
    $this->row++;
  }
  public function clear($i)
  {
    array_splice($this->file_sequences, $i, 1);
    array_splice($this->file_link, $i, 1);
    array_splice($this->file_name, $i, 1);
    $this->row--;
  }
  public function saveexternal()
  {
    $data = Category::find($this->categoryId);
    $productType = class_basename(get_class($data));

    for ($i = 1; $i <= $this->row; $i++) {
      $this->resetErrorBag();
      $this->validate([
        'file_sequences.*' => 'required',
        'file_locations.*' => 'required',
        'file_link.*' => 'required|url',
        'file_name.*' => 'required'
      ]);
      $media = new Media();
      $media->name = $this->file_name[$i];
      $media->sequence = $this->file_sequences[$i];
      $media->location_id = $this->file_locations[$i];
      $media->path = $this->file_link[$i];
      $media->external = true;
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->item_id = $this->productId;
      $media->tabel_id = Tabels::where('name', $productType)->first()->id;
      $media->save();
    }
    session()->flash('message', 'Media Update Successfully!');
    $this->row = 0;
    $this->externalmedia = false;
    $this->file_sequences = [];
    $this->file_link = [];
    $this->file_name = [];
  }
  public function editMedia($mediaIndex)
  {
    $this->editedMediaIndex = $mediaIndex;
  }
  public function cancelMedia()
  {
    $this->editedMediaIndex = null;
    $this->filess = [];
  }
  public function saveMedia($mediaIndex, $id)
  {
    $media_new = $this->filess[$mediaIndex] ?? NULL;
    if (!is_null($media_new)) {
      $media_for_cat = Media::find($id);
      if (array_key_exists('sequence', $media_new)) {
        $media_for_cat->sequence = $media_new['sequence'];
      }
      if (array_key_exists('location_id', $media_new)) {
        $media_for_cat->location_id = $media_new['location_id'];
      }
      if (array_key_exists('name', $media_new)) {
        $newName = $media_new['name'] . '.' . $media_for_cat->type;
        $oldName = $media_for_cat->name;
        if ($newName !== $oldName) {
          $path = $media_for_cat->path;
          if (file_exists($path . $newName)) {
            $i = 1;
            while (file_exists($path . $media_new['name'] . '(' . $i . ').' . $media_for_cat->type)) {
              $i++;
            }
            $newName = $media_new['name'] . '(' . $i . ').' . $media_for_cat->type;
          }
          $oldFilePath = $path . $oldName;
          $newFilePath = $path . $newName;
          $media_for_cat->name = $newName;
          $media_for_cat->save();
          if (file_exists($oldFilePath)) {
            rename($oldFilePath, $newFilePath);
          }
        }
      }
      $media_for_cat->save();
      session()->flash('message', 'Media Edited Successfully!');
    }
    $this->filess = [];
    $this->editedMediaIndex = null;
  }
  public function showColumn($column)
  {
    if ($column === 'Name') {
      return true;
    }
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
  public function save()
  {
    $this->validate([
      'medias.*' => 'mimetypes:image/jpeg,image/png,image/svg+xml,video/mp4,video/quicktime|max:10240', // Max 10MB for all files
    ]);
    $data = Category::find($this->categoryId);
    $productType = class_basename(get_class($data));
    $filespath = 'media/' . $productType . '/';
    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }
    if (!File::exists($filespath . "$data->id")) {
      File::makeDirectory($filespath . "$data->id", 0755, true);
    }
    $path = $filespath . "$this->categoryId" . "/";
    $this->i = 0;
    foreach ($this->medias as $file) {
      $media = new Media();
      $type = $file->getClientOriginalExtension();
      if ($type === 'mp4' || $type === 'ogg') {
        $filePath = $file->getRealPath();
        $contents = Storage::get($filePath);
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($contents);
        if (isset($fileInfo['video']) && isset($fileInfo['video']['resolution_x']) && isset($fileInfo['video']['resolution_y'])) {
          $width = $fileInfo['video']['resolution_x'];
          $height = $fileInfo['video']['resolution_y'];
        } else {
          $width = "unnable to get";
          $height = "unnable to get";
        }
      } elseif ($type === 'svg') {
        $svg = simplexml_load_file($file->getRealPath());
        $width = (string) $svg['width'];
        $height = (string) $svg['height'];
      } else {
        $image = Image::make($file);
        $width = $image->width();
        $height = $image->height();
      }
      $media->item_id = $data->id;
      $media->path = $path;
      $media->name = $file->getClientOriginalName();
      $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $media->name = $filename . '.' . $type;
      if (file_exists($path . $media->name)) {
        $this->j = 1;
        while (file_exists($path . $filename . '(' . $this->j . ').' . $type)) {
          $this->j++;
        }
        $media->name = $filename . '(' . $this->j . ').' . $type;
      }
      $file->storeAs($path, $media->name, 'public_upload');
      $media->tabel_id = Tabels::where('name', $productType)->first()->id;
      $media->sequence = $this->file_sequences[$this->i];
      $media->location_id = MediaLocation::where('id', $this->file_locations[$this->i])->first()->id;
      $media->type = $type;
      $media->width = $width;
      $media->height =  $height;
      $media->external = false;
      $media->size = $file->getSize();
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->save();
      $this->i += 1;
    }
    $this->medias = [];
    session()->flash('message', 'Media Update Successfully!');
  }
  public function removemedia($index)
  {
    array_splice($this->medias, $index, 1);
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
  public function deleteSingleRecord()
  {
    $media = Media::findOrFail($this->mediaidbeingremoved);
    $path = $media->path . $media->name;
    if (File::exists($path)) {
      File::delete($path);
    }
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
    return $this->filesQuery->paginate($this->perPage);
  }
  public function getFilesQueryProperty()
  {
    return Media::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->where('item_id', $this->categoryId)->where('tabel_id', $this->type)->with('location');
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
  public function confirmFilesRemovalmultiple()
  {

    $this->dispatchBrowserEvent('show-delete-modal-media-multiple');
  }
  public function exportSelected()
  {
    $export = new MediasExport($this->checked);
    $this->checked = [];
    $this->selectPage = false;
    return $export->download('medias.xlsx');
  }
  public function render()
  {
    $this->hasResults = $this->files->isNotEmpty();
    return view('livewire.related-media-category', [
      'files' => $this->files
    ]);
  }
}
