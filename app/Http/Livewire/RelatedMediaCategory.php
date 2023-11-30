<?php

namespace App\Http\Livewire;

use getID3;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
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
  public $columns = ['Id', 'Media', 'Media Location', 'Sequence', 'Created At'];
  public $selectedColumns = [];
  public $locations;
  public $file_sequences = ['0'];
  public $file_locations = [];
  public $file_link = [];
  public $file_name = [];
  public $col = false;
  public $all = false;
  public $editedMediaIndex = null;
  public $i;
  public $j;
  public $row = 1;
  public $externalmedia = false;

  public function mount($category)
  {
    $this->categoryId = $category->id;
    $this->category = $category;
    $this->selectedColumns = $this->columns;
    $this->locations = MediaLocation::all();
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
    for ($i = 1; $i <= $this->row; $i++) {
      $this->file_locations[$i] = $this->locations->first()->id;
    }
  }
  public function plus()
  {
    $this->row++;
    for ($i = 1; $i <= $this->row; $i++) {
      $this->file_locations[$i] = $this->locations->first()->id;
    }
  }
  public function clear($i)
  {
    array_splice($this->file_sequences, $i, 1);
    array_splice($this->file_link, $i, 1);
    array_splice($this->file_name, $i, 1);
    array_splice($this->file_locations, $i, 1);
    $this->row--;

    // Reindex the arrays
    $this->file_sequences = array_values($this->file_sequences);
    $this->file_link = array_values($this->file_link);
    $this->file_name = array_values($this->file_name);
    $this->file_locations = array_values($this->file_locations);
  }
  public function saveexternal()
  {
    for ($this->i = 1; $this->i <= $this->row; $this->i++) {
      $this->resetErrorBag();
      $this->validate([
        'file_sequences.*' => 'required',
        'file_locations.*' => 'required',
        'file_link.*' => 'required|url',
        'file_name.*' => 'required'
      ]);
      $media = new Media();
      $media->name = $this->file_name[$this->i];
      $media->sequence = $this->file_sequences[$this->i];
      $media->location_id = $this->file_locations[$this->i];
      $media->path = $this->file_link[$this->i];
      $media->external = true;
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->save();
      $this->category->media()->attach($media->id);
    }
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->row = 0;
    $this->externalmedia = false;
    $this->file_sequences = [];
    $this->file_link = [];
    $this->file_name = [];
  }
  public function editMedia($index, $id)
  {
    $this->editedMediaIndex = $index;
    $media = Media::find($id);
    if ($media->external == 1) {
      $this->filess = [
        $index . '.path' => $media->path,
        $index . '.name' => $media->name,
        $index . '.location_id' => $media->location_id,
        $index . '.sequence' => $media->sequence,
      ];
    } else {
      $this->filess = [
        $index . '.name' => $media->name,
        $index . '.location_id' => $media->location_id,
        $index . '.sequence' => $media->sequence,
      ];
    }
  }
  public function cancel()
  {
    $this->medias = [];
    $this->file_sequences = ['0'];
    $this->file_locations = [];
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
      $media_for_prod = Media::find($id);
      if ($media_for_prod->external == 1) {
        if (array_key_exists('path', $media_new)) {
          $media_for_prod->path = $media_new['path'];
        }
      }
      if (array_key_exists('sequence', $media_new)) {
        $media_for_prod->sequence = $media_new['sequence'];
      }
      if (array_key_exists('location_id', $media_new)) {
        $media_for_prod->location_id = $media_new['location_id'];
      }
      if (array_key_exists('name', $media_new)) {
        $newName = $media_new['name'] . '.' . $media_for_prod->type;
        $oldName = $media_for_prod->name;
        if ($newName !== $oldName) {
          $path = $media_for_prod->path;
          if (file_exists($path . $newName)) {
            $i = 1;
            while (file_exists($path . $media_new['name'] . '(' . $i . ').' . $media_for_prod->type)) {
              $i++;
            }
            $newName = $media_new['name'] . '(' . $i . ').' . $media_for_prod->type;
          }
          $oldFilePath = $path . $oldName;
          $newFilePath = $path . $newName;
          $media_for_prod->name = $newName;
          $media_for_prod->save();
          if (file_exists($oldFilePath)) {
            rename($oldFilePath, $newFilePath);
          }
        }
      }
      $media_for_prod->save();
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
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
      $this->checked = $this->category->media()->pluck('media.id')->map(fn ($item) => (string) $item)->toArray();
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
      'medias.*' => 'mimetypes:image/jpeg,image/png,image/webp,image/svg+xml,video/mp4,video/quicktime|max:10240', // Max 10MB for all files
    ]);
    $productType = class_basename(get_class($this->category));
    $filespath = 'media/' . $productType . '/';
    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }
    if (!File::exists($filespath . "$this->categoryId")) {
      File::makeDirectory($filespath . "$this->categoryId", 0755, true);
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
      $this->category->media()->attach($media->id);
      $this->i += 1;
    }
    $this->medias = [];
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->category->media()->pluck('media.id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function confirmItemRemoval($id)
  {
    $this->mediaidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function confirmItemsRemoval()
  {

    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function render()
  {
    $filteredMedia = $this->category->media()
      ->where('name', 'LIKE', '%' . $this->search . '%')
      ->get();

    return view('livewire.related-media-category', [
      'category' => $this->category,
      'filteredMedia' => $filteredMedia,
    ]);
  }
}
