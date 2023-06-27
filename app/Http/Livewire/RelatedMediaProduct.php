<?php

namespace App\Http\Livewire;

use getID3;
use App\Models\Media;
use App\Models\Tabels;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\MediasExport;
use App\Models\MediaLocation;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class RelatedMediaProduct extends Component
{

  use WithFileUploads;
  use WithPagination;
  public $productId;
  public $product;
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
  public $col = false;
  public $all = false;
  public $hasResults;
  public $editedMediaIndex = null;
  public $i;
  public $j;
  public $count = 0;

  public function mount($productId)
  {
    $this->productId = $productId;
    $this->product = Product::find($productId);
    $this->productType = class_basename(get_class($this->product));
    $this->type = Tabels::where('name', $this->productType)->first()->id;
    $this->selectedColumns = $this->columns;
    $this->locations = MediaLocation::all();
    $this->file_locations[] = '1';
    $this->count = Media::where('item_id', $this->productId)->where('tabel_id', $this->type)->count();
    $this->i = null;
    $this->j = null;
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
    if(!is_null($media_new)){
      $media_for_prod = Media::find($id);
      if (array_key_exists('sequence', $media_new)) {
        $media_for_prod->sequence = $media_new['sequence'];
    }
      if(array_key_exists('location_id', $media_new)){
      $media_for_prod->location_id = $media_new['location_id'];
    }
    if (array_key_exists('name', $media_new)) {
      $newName = $media_new['name'] . '.' . $media_for_prod->type;

      $oldName = $media_for_prod->name;

      if ($newName !== $oldName) {


          // Rename the file in the file directory
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
           // Update the name attribute
           $media_for_prod->name = $newName;
           $media_for_prod->save();

          if (file_exists($oldFilePath)) {
              rename($oldFilePath, $newFilePath);
          }
      }
  }
      $media_for_prod->save();
      session()->flash('message', 'Media Edited Successfully!');
    }
    $this->filess = [];
    $this->editedMediaIndex = null;

  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }

  public function showColumn($column)
  {

     // Always show the "Name" column
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

  public function save()
  {
    $this->validate([
      'medias.*' => 'mimetypes:image/jpeg,image/png,image/svg+xml,video/mp4,video/quicktime|max:10240', // Max 10MB for all files
    ]);
    $data = Product::find($this->productId);
    $productType = class_basename(get_class($data));
    $filespath = 'media/' . $productType . '/';
    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }

    //verify and create a folder with product id name
    if (!File::exists($filespath . "$data->id")) {
      File::makeDirectory($filespath . "$data->id", 0755, true);
    }
    $path = $filespath . "$this->productId" . "/";
    $this->i = 0;
    foreach ($this->medias as $file) {
      $media = new Media();
      //verify the type of media
      $type = $file->getClientOriginalExtension();

      if ($type === 'mp4' || $type === 'ogg') {
        $filePath = $file->getRealPath();
        $contents = Storage::get($filePath);
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($contents);
        if (isset($fileInfo['video']) && isset($fileInfo['video']['resolution_x']) && isset($fileInfo['video']['resolution_y'])) {
          // Retrieve the width and height
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
      //save the path and the name
      $media->item_id = $data->id;
      $media->path = $path;
      $media->name = $file->getClientOriginalName();
      //store the media
      $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $media->name = $filename . '.' . $type;
      // Verify if media name exist
      if (file_exists($path . $media->name)) {
        $this->j = 1;
        while (file_exists($path . $filename . '(' . $this->j . ').' . $type)) {
          $this->j++;
        }
        $media->name = $filename . '(' . $this->j . ').' . $type;
      }

      $file->storeAs($path, $media->name, 'public_upload');
      $media->tabel_id = Tabels::where('name', $productType)->first()->id;
      // dd($this->file_sequences[$i]);
      $media->sequence = $this->file_sequences[$this->i ];
      $media->location_id = MediaLocation::where('id', $this->file_locations[$this->i ])->first()->id;
      $media->type = $type;
      $media->width = $width;
      $media->height =  $height;
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
    return $this->filesQuery->paginate($this->perPage);
  }
  public function getFilesQueryProperty()
  {
    return Media::orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->where('item_id', $this->productId)->where('tabel_id', $this->type)->with('location');
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
        return view('livewire.related-media-product', [
          'files' => $this->files,
          'count' => $this->count
        ]);
    }
}
