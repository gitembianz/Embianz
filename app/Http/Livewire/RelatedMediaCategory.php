<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;


class RelatedMediaCategory extends Component
{
  use WithFileUploads;
  use WithPagination;
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
  public $idbeingremoved = null;
  public $columns = [];
  public $selectedColumns = [];
  public $file_sequences = [];
  public $file_link = [];
  public $file_name = [];
  public $editedMediaIndex = null;
  public $i;
  public $j;
  public $row = 1;
  public $externalmedia = false;


  public $chose = false;

  public $single = false;
  public $multiple = false;
  public $rind = null;
  public $rind2 = null;
  public $rind3 = null;

  public function expandRow($index)
  {
    if ($this->rind  === null) {
      $this->rind = $index;
    } elseif ($this->rind != $index) {
      $this->rind = $index;
    } else {
      $this->rind = null;
    }
  }
  public function expandRow2($index)
  {
    if ($this->rind2  === null) {
      $this->rind2 = $index;
    } elseif ($this->rind2 != $index) {
      $this->rind2 = $index;
    } else {
      $this->rind2 = null;
    }
  }
  public function expandRow3($index)
  {
    if ($this->rind3  === null) {
      $this->rind3 = $index;
    } elseif ($this->rind3 != $index) {
      $this->rind3 = $index;
    } else {
      $this->rind3 = null;
    }
  }



  public function mount(Category $category)
  {
    $this->category = $category;
    $this->columns = Schema::getColumnListing('media');
    $this->selectedColumns = $this->columns;
    $this->i = null;
    $this->j = null;
  }
  public function uploadmedia()
  {
    $this->showmedia = true;
    $this->chose = true;
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
    $this->row = 0;
    $this->externalmedia = true;
    $this->file_name[$this->row] = null;
    $this->file_sequences[$this->row] = null;
    $this->file_link[$this->row] = null;
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

    if ($this->row < 0) {
      $this->externalmedia = false;
    }
  }
  public function saveexternal()
  {
    $productType = class_basename(get_class($this->category));
    //check for directory
    $filespath = 'media/' . $productType . '/';
    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }
    if (!File::exists($filespath . $this->category->id)) {
      File::makeDirectory($filespath . $this->category->id, 0755, true);
    }
    $path = $filespath . $this->category->id . "/";

    for ($this->i = 0; $this->i <= $this->row; $this->i++) {
      $this->resetErrorBag();
      $this->validate([
        'file_sequences.*' => 'required',
        'file_link.*' => 'required|url',
        'file_name.*' => 'required'
      ]);
      $urlComponents = parse_url($this->file_link[$this->i]);

      $urlWithoutParams = $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
      $this->file_link[$this->i] = $urlWithoutParams;
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
      $fileExtension = strtolower(pathinfo($this->file_link[$this->i], PATHINFO_EXTENSION));

      if (!in_array($fileExtension, $allowedExtensions)) {
        continue;
      }
      $fileContent = file_get_contents($this->file_link[$this->i]);
      if ($fileContent == false) {
        continue;
      }
      $imageInfo = getimagesizefromstring($fileContent);
      //extension

      if (app()->has('global_auto_webp') &&  app('global_auto_webp') == 'true') {
        $image = Image::make($fileContent);
        $webpContent = $image->encode('webp')->__toString();
        $fileExtension = 'webp';
        $name = strtolower(preg_replace('/\s+/', '-', $this->file_name[$this->i])) . '.' . $fileExtension;
        if (file_exists($path . $name)) {
          $this->j = 1;
          while (file_exists($path . strtolower(preg_replace('/\s+/', '-', $this->file_name[$this->i])) . '(' . $this->j . ').' . $fileExtension)) {
            $this->j++;
          }
          $name = strtolower(preg_replace('/\s+/', '-', $this->file_name[$this->i])) . '(' . $this->j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $webpContent);
      } else {
        $fileExtension = image_type_to_extension($imageInfo[2], false);
        $name = strtolower(preg_replace('/\s+/', '-', $this->file_name[$this->i])) . '.' . $fileExtension;
        if (file_exists($path . $name)) {
          $this->j = 1;
          while (file_exists($path . strtolower(preg_replace('/\s+/', '-', $this->file_name[$this->i])) . '(' . $this->j . ').' . $fileExtension)) {
            $this->j++;
          }
          $name = strtolower(preg_replace('/\s+/', '-', $this->file_name[$this->i])) . '(' . $this->j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $fileContent);
      }

      $filePath = $path . $name;
      $file = Storage::disk('public_upload')->get($filePath);
      if ($this->file_sequences[$this->i] == '1') {
        $ismin = $this->category->media()->where('type', 'min')->first();

        if (!$ismin) {

          $resizedImage = Image::make($file)
            ->resize(70, 70, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            });

          $resizedImage->encode('webp')->save($path . "resized70_" . $name);
          $media = new Media();
          $media->path = $path;
          $media->name = "resized70_" . $name;
          $media->sequence = $this->file_sequences[$this->i];
          $media->extension = $fileExtension;
          $media->width = $resizedImage->width();
          $media->height = $resizedImage->height();
          $media->type = 'min';
          $media->size = strlen($fileContent);
          $media->createdby = Auth::user()->name;
          $media->lastmodifiedby = Auth::user()->name;
          $media->save();
          $this->category->media()->attach($media->id);
        } else {
          $oldPath = $ismin->path . $ismin->name;
          if (File::exists($oldPath)) {
            File::delete($oldPath);
          }

          $resizedImage = Image::make($file)
            ->resize(70, 70, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            });

          $newPath = $ismin->path . "resized70_" . $name;
          $resizedImage->encode('webp')->save($newPath);
          $ismin->path = $ismin->path;
          $ismin->name = "resized70_" . $name;
          $ismin->sequence = $this->file_sequences[$this->i];
          $ismin->extension = $fileExtension;
          $ismin->width = $resizedImage->width();
          $ismin->height = $resizedImage->height();
          $ismin->size = File::size($newPath);
          $ismin->lastmodifiedby = Auth::user()->name;
          $ismin->save();
        }
        $oldPath = $path . $name;
        File::delete($oldPath);
      } else {
        $media = new Media();
        $media->path = $path;
        $media->name = $name;
        $media->sequence =
          $this->file_sequences[$this->i];
        $media->extension = $fileExtension;
        $media->type = 'original';
        $image = Image::make($file);
        $width = $image->width();
        $height = $image->height();
        $media->width = $width;
        $media->height = $height;
        $media->size = File::size($path . $name);
        $media->createdby = Auth::user()->name;
        $media->lastmodifiedby = Auth::user()->name;
        $media->save();
        $this->category->media()->attach($media->id);
      }
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
    $this->chose = false;
  }
  public function editMedia($index, $id)
  {
    $this->editedMediaIndex = $index;
    $media = Media::find($id);

    $this->filess = [
      $index . '.name' => $media->name,
      $index . '.sequence' => $media->sequence,
    ];
  }
  public function cancel()
  {
    $this->medias = [];
    $this->file_sequences = [];
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
      if (array_key_exists('sequence', $media_new)) {
        $media_for_prod->sequence = $media_new['sequence'];
      }
      if (array_key_exists('name', $media_new)) {
        $newName = $media_new['name'] . '.' . $media_for_prod->extension;
        $oldName = $media_for_prod->name;
        if ($newName !== $oldName) {
          $path = $media_for_prod->path;
          if (file_exists($path . $newName)) {
            $i = 1;
            while (file_exists($path . $media_new['name'] . '(' . $i . ').' . $media_for_prod->extension)) {
              $i++;
            }
            $newName = $media_new['name'] . '(' . $i . ').' . $media_for_prod->extension;
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

    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->category->media()->pluck('media.id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function savelocal()
  {
    $productType = class_basename(get_class($this->category));
    $filespath = 'media/' . $productType . '/';
    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }
    if (!File::exists($filespath . $this->category->id)) {
      File::makeDirectory($filespath . $this->category->id, 0755, true);
    }
    $path = $filespath . $this->category->id . "/";
    foreach ($this->medias as $index => $file) {
      if (app()->has('global_auto_webp') &&  app('global_auto_webp') == 'true') {
        $type = 'webp';
      } else {
        $type = $file->getClientOriginalExtension();
      }
      $filename = strtolower(preg_replace('/\s+/', '-', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)));
      $name = $filename . '.' . $type;
      if (file_exists($path . $name)) {
        $this->j = 1;
        while (file_exists($path . $filename . '(' . $this->j . ').' . $type)) {
          $this->j++;
        }
        $name = $filename . '(' . $this->j . ').' . $type;
      }
      if ($this->file_sequences[$index] == '1') {
        $ismin = $this->category->media()->where('type', 'min')->first();

        if (!$ismin) {
          $resizedImage = Image::make($file)
            ->resize(70, 70, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            });

          $resizedImage->encode('webp')->save($path . "resized70_" . $name);
          $media = new Media();
          $media->path = $path;
          $media->name = "resized70_" . $name;
          $media->sequence = $this->file_sequences[$index];
          $media->extension = $type;
          $media->width = $resizedImage->width();
          $media->height = $resizedImage->height();
          $media->type = 'min';
          $media->size = $file->getSize();
          $media->createdby = Auth::user()->name;
          $media->lastmodifiedby = Auth::user()->name;
          $media->save();
          $this->category->media()->attach($media->id);
        } else {
          $oldPath = $ismin->path . $ismin->name;
          if (File::exists($oldPath)) {
            File::delete($oldPath);
          }

          $resizedImage = Image::make($file->getRealPath())
            ->resize(70, 70, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            });

          $newPath = $ismin->path . "resized70_" . $name;
          $resizedImage->encode('webp')->save($newPath);
          $ismin->path = $ismin->path;
          $ismin->name = "resized70_" . $name;
          $ismin->sequence = $this->file_sequences[$index];
          $ismin->extension = $type;
          $ismin->width = $resizedImage->width();
          $ismin->height = $resizedImage->height();
          $ismin->size = File::size($newPath);
          $ismin->lastmodifiedby = Auth::user()->name;
          $ismin->save();
        }
      } else {
        $file->storeAs($path, $name, 'public_upload');
        $media = new Media();
        $image = Image::make($file);
        $width = $image->width();
        $height = $image->height();
        $media->path = $path;
        $media->name = $name;
        $media->sequence = $this->file_sequences[$index];
        $media->type = 'original';
        $media->extension = $type;
        $media->width = $width;
        $media->height =  $height;
        $media->size = $file->getSize();
        $media->createdby = Auth::user()->name;
        $media->lastmodifiedby = Auth::user()->name;
        $media->save();
        $this->category->media()->attach($media->id);
      }
    }
    $this->medias = [];
    $this->file_sequences = [];
    $this->chose = false;
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function removemedia($index)
  {
    array_splice($this->file_sequences, $index, 1);
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
    $media = Media::findOrFail($this->idbeingremoved);
    $path = $media->path . $media->name;
    if (File::exists($path)) {
      File::delete($path);
    }
    $media->delete();
    $folder = $media->path;
    if (File::isDirectory($folder) && count(File::allFiles($folder)) === 0) {
      File::deleteDirectory($folder);
    }
    $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
    $this->single = false;
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function cancel_chose()
  {
    $this->chose = false;
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
    $this->multiple = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->category->media()->pluck('media.id')->map(fn($item) => (string) $item)->toArray();
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function confirmItemRemoval($id)
  {
    $this->idbeingremoved = $id;
    $this->single = true;
  }
  public function confirmItemsRemoval()
  {
    $this->multiple = true;
  }
  public function cancel_delete()
  {
    $this->multiple = false;
    $this->single = false;
  }
  public function render()
  {
    $filteredMedia = $this->category->media()
      ->where('name', 'LIKE', '%' . $this->search . '%')
      ->get();

    return view('livewire.related-media-category', [
      'filteredMedia' => $filteredMedia
    ]);
  }
}
