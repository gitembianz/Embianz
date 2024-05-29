<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class RelatedMediaProduct extends Component
{

  use WithFileUploads;
  use WithPagination;
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
  public $file_sequences = [];
  public $file_resize = [];
  public $file_link = [];
  public $file_name = [];
  public $col = false;
  public $all = false;
  public $editedMediaIndex = null;
  public $i;
  public $j;
  public $row = 1;
  public $externalmedia = false;
  public $initiate = false;

  public function mount(Product $product)
  {
    $this->product = $product;
    $this->productType = class_basename(get_class($this->product));
    $this->selectedColumns = $this->columns;
    $this->i = null;
    $this->j = null;
  }
  public function editMedia($index, $id)
  {
    $this->editedMediaIndex = $index;
    $media = Media::find($id);
    if ($media->external == 1) {
      $this->filess = [
        $index . '.path' => $media->path,
        $index . '.name' => $media->name,
        $index . '.sequence' => $media->sequence,
      ];
    } else {
      $this->filess = [
        $index . '.name' => $media->name,
        $index . '.sequence' => $media->sequence,
      ];
    }
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
  public function uploadmedia()
  {
    $this->showmedia = true;
    $this->dispatchBrowserEvent('media');
  }
  public function external()
  {
    $this->row = 0;
    $this->externalmedia = true;
    $this->file_name[$this->row] = null;
    $this->file_sequences[$this->row] = null;
    $this->file_link[$this->row] = null;
    $this->file_resize[$this->row] = false;
  }
  public function plus()
  {
    $this->row++;
    $this->file_name[$this->row] = null;
    $this->file_sequences[$this->row] = null;
    $this->file_link[$this->row] = null;
    $this->file_resize[$this->row] = false;
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
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
      $this->checked = $this->product->media()->pluck('media.id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function clearall()
  {
    $this->row = 0;
    $this->externalmedia = false;
    $this->file_sequences = [];
    $this->file_link = [];
    $this->file_name = [];
  }
  public function clear($i)
  {
    array_splice($this->file_sequences, $i, 1);
    array_splice($this->file_link, $i, 1);
    array_splice($this->file_name, $i, 1);
    array_splice($this->file_resize, $i, 1);
    $this->row--;

    if ($this->row < 0) {
      $this->externalmedia = false;
    }
  }
  public function saveexternal()
  {
    $productType = class_basename(get_class($this->product));
    //check for directory
    $filespath = 'media/' . $productType . '/';
    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }
    if (!File::exists($filespath . $this->product->id)) {
      File::makeDirectory($filespath . $this->product->id, 0755, true);
    }
    $path = $filespath . $this->product->id . "/";

    for ($i = 0; $i <= $this->row; $i++) {
      $this->resetErrorBag();
      $this->validate([
        'file_sequences.*' => 'required',
        'file_link.*' => 'required|url',
        'file_name.*' => 'required'
      ]);
      $urlComponents = parse_url($this->file_link[$i]);

      $urlWithoutParams = $urlComponents['scheme'] . '://' . $urlComponents['host'] . $urlComponents['path'];
      $this->file_link[$i] = $urlWithoutParams;
      $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
      $fileExtension = strtolower(pathinfo($this->file_link[$i], PATHINFO_EXTENSION));

      if (!in_array($fileExtension, $allowedExtensions)) {
        continue;
      }
      $fileContent = file_get_contents($this->file_link[$i]);
      if ($fileContent == false) {
        continue;
      }
      $imageInfo = getimagesizefromstring($fileContent);
      if (app()->has('global_auto_webp') &&  app('global_auto_webp') == 'true') {
        $image = Image::make($fileContent);
        $webpContent = $image->encode('webp')->__toString();
        $fileExtension = 'webp';
        $name = $this->file_name[$i] . '.' . $fileExtension;
        if (file_exists($path . $name)) {
          $this->j = 1;
          while (file_exists($path . $this->file_name[$i] . '(' . $this->j . ').' . $fileExtension)) {
            $this->j++;
          }
          $name = $this->file_name[$i] . '(' . $this->j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $webpContent);
      } else {
        $fileExtension = image_type_to_extension($imageInfo[2], false);
        $name = $this->file_name[$i] . '.' . $fileExtension;
        if (file_exists($path . $name)) {
          $this->j = 1;
          while (file_exists($path . $this->file_name[$i] . '(' . $this->j . ').' . $fileExtension)) {
            $this->j++;
          }
          $name = $this->file_name[$i] . '(' . $this->j . ').' . $fileExtension;
        }
        Storage::disk('public_upload')->put($path . $name, $fileContent);
      }

      $media = new Media();
      $media->name = $name;
      $media->extension = $fileExtension;
      $media->width = $imageInfo[0];
      $media->height =  $imageInfo[1];
      $media->size = strlen($fileContent);
      $media->type = 'original';
      $media->sequence = $this->file_sequences[$i];
      $media->path = $path;
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->save();
      $this->product->media()->attach($media->id);

      //Resize system
      $filePath = $path . $name;
      $file = Storage::disk('public_upload')->get($filePath);

      // Set the file content
      if ($this->file_resize[$i]) {
        //Resize system
        //Min image -Search
        if ($this->file_sequences[$i] == '1') {
          $ismin = $this->product->media()->where('type', 'min')->first();

          if (!$ismin) {
            $this->resizeImage(
              $file,
              $path,
              70,
              'min',
              $name,
              $fileExtension,
              true,
              $this->file_sequences[$i]
            );
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
            $ismin->sequence = $this->file_sequences[$i];
            $ismin->extension = $fileExtension;
            $ismin->width = $resizedImage->width();
            $ismin->height = $resizedImage->height();
            $ismin->size = File::size($newPath);
            $ismin->lastmodifiedby = Auth::user()->name;
            $ismin->save();
          }

          $ismaim = $this->product->media()->where('type', 'main')->first();
          if (!$ismaim) {
            $this->resizeImage($file, $path, 300, 'main', $name, $fileExtension, true, $this->file_sequences[$i]);
          } else {
            $oldPath = $ismaim->path . $ismaim->name;
            if (File::exists($oldPath)) {
              File::delete($oldPath);
            }

            $resizedImage = Image::make($file)
              ->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
              });

            $newPath = $ismaim->path . "resized300_" . $name;
            $resizedImage->encode('webp')->save($newPath);
            $ismaim->path = $ismaim->path;
            $ismaim->name = "resized300_" . $name;
            $ismaim->sequence = $this->file_sequences[$i];
            $ismaim->extension = $fileExtension;
            $ismaim->width = $resizedImage->width();
            $ismaim->height = $resizedImage->height();
            $ismaim->size = File::size($newPath);
            $ismaim->lastmodifiedby = Auth::user()->name;
            $ismaim->save();
          }
        }


        $this->resizeImage($file, $path, 640, 'full', $name, $fileExtension, true, $this->file_sequences[$i]);
      }


      session()->flash('notification', [
        'message' => 'Record related successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
    $this->row = 0;
    $this->externalmedia = false;
    $this->file_sequences = [];
    $this->file_link = [];
    $this->file_name = [];
    $this->mount($this->product);
  }
  public function save()
  {
    //local media saved
    $productType = class_basename(get_class($this->product));
    //check for directory
    $filespath = 'media/' . $productType . '/';
    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }
    if (!File::exists($filespath . $this->product->id)) {
      File::makeDirectory($filespath . $this->product->id, 0755, true);
    }
    $path = $filespath . $this->product->id . "/";
    //media handdleer
    $this->i = 0;
    foreach ($this->medias as $file) {
      $media = new Media();
      if (app()->has('global_auto_webp') &&  app('global_auto_webp') == 'true') {
        $type = 'webp';
      } else {
        $type = $file->getClientOriginalExtension();
      }
      $image = Image::make($file);
      $width = $image->width();
      $height = $image->height();
      $media->path = $path;
      $media->name = $file->getClientOriginalName();
      //name-checker
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
      $media->type = 'original';
      $media->extension = $type;
      $media->width = $width;
      $media->height =  $height;
      $media->size = $file->getSize();
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->save();
      $this->product->media()->attach($media->id);
      if ($this->file_resize[$this->i]) {
        //Resize system
        //Min image -Search
        if ($this->file_sequences[$this->i] == '1') {
          $ismin = $this->product->media()->where('type', 'min')->first();

          if (!$ismin) {
            $this->resizeImage(
              $file,
              $path,
              70,
              'min',
              $media->name,
              $type,
              false,
              $this->file_sequences[$this->i]
            );
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

            $newPath = $ismin->path . "resized70_" . $media->name;
            $resizedImage->encode('webp')->save($newPath);
            $ismin->path = $ismin->path;
            $ismin->name = "resized70_" . $media->name;
            $ismin->sequence = $this->file_sequences[$this->i];
            $ismin->extension = $type;
            $ismin->width = $resizedImage->width();
            $ismin->height = $resizedImage->height();
            $ismin->size = File::size($newPath);
            $ismin->lastmodifiedby = Auth::user()->name;
            $ismin->save();
          }

          $ismaim = $this->product->media()->where('type', 'main')->first();
          if (!$ismaim) {
            $this->resizeImage($file, $path, 300, 'main', $media->name, $type, false, $this->file_sequences[$this->i]);
          } else {
            $oldPath = $ismaim->path . $ismaim->name;
            if (File::exists($oldPath)) {
              File::delete($oldPath);
            }

            $resizedImage = Image::make($file->getRealPath())
              ->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
              });

            $newPath = $ismaim->path . "resized300_" . $media->name;
            $resizedImage->encode('webp')->save($newPath);
            $ismaim->path = $ismaim->path;
            $ismaim->name = "resized300_" . $media->name;
            $ismaim->sequence = $this->file_sequences[$this->i];
            $ismaim->extension = $type;
            $ismaim->width = $resizedImage->width();
            $ismaim->height = $resizedImage->height();
            $ismaim->size = File::size($newPath);
            $ismaim->lastmodifiedby = Auth::user()->name;
            $ismaim->save();
          }
        }


        $this->resizeImage($file, $path, 640, 'full', $media->name, $type, false, $this->file_sequences[$this->i]);
      }
      $this->i += 1;
    }
    $this->medias = [];
    $this->initiate = false;
    $this->file_sequences = [];
    $this->file_resize = [];
    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  private function resizeImage($file, $path, $size, $type, $name, $extension, $external, $sequence)
  {
    if ($external) {
      $resizedImage = Image::make($file)
        ->resize($size, $size, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
    } else {

      $resizedImage = Image::make($file->getRealPath())
        ->resize($size, $size, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
        });
    }

    $resizedImage->encode('webp')->save($path . "resized{$size}_" . $name);

    $resizedMedia = new Media();
    $resizedMedia->path = $path;
    $resizedMedia->name = "resized{$size}_" . $name;
    $resizedMedia->sequence = $sequence;
    $resizedMedia->extension = $extension;
    $resizedMedia->type = $type;
    $resizedMedia->width = $resizedImage->width();
    $resizedMedia->height = $resizedImage->height();
    $resizedMedia->size = File::size($path . "resized{$size}_" . $name);
    $resizedMedia->createdby = Auth::user()->name;
    $resizedMedia->lastmodifiedby = Auth::user()->name;
    $resizedMedia->save();

    $this->product->media()->attach($resizedMedia->id);
  }
  public function updatingMedias($value)
  {
    if ($this->initiate == false) {
      $mediaCount = count($value);
      $this->file_resize = [];
      for ($i = 0; $i <= $mediaCount; $i++) {
        $this->file_resize[$i] = false;
      }
      $this->initiate = true;
    }
  }
  public function removemedia($index)
  {
    // Use unset to remove the item at the specified index
    array_splice($this->file_sequences, $index, 1);
    array_splice($this->file_resize, $index, 1);
    array_splice($this->medias, $index, 1);
  }

  public function cancel()
  {
    $this->medias = [];
    $this->file_sequences = [];
    $this->file_resize = [];
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
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteRecords()
  {
    $medias = Media::whereKey($this->checked)->get();
    foreach ($medias as $media) {
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
      'message' => 'Records related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->product->media()->pluck('media.id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function confirmRemoval($id)
  {
    $this->mediaidbeingremoved = $id;
    $this->dispatchBrowserEvent('delete-media');
  }
  public function confirmFilesRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function render()
  {
    $filteredMedia = $this->product->media()
      ->where('name', 'LIKE', '%' . $this->search . '%')
      ->get();

    return view('livewire.related-media-product', [
      'filteredMedia' => $filteredMedia
    ]);
  }
}
