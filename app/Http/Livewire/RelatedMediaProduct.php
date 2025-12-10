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
use Illuminate\Support\Facades\Schema;


class RelatedMediaProduct extends Component
{

  use WithFileUploads;
  use WithPagination;
  public $product;
  public $showmedia = false;
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
  public $idbeingremoved = null;
  public $columns = [];
  public $selectedColumns = [];
  public $locations;
  public $file_sequences = [];
  public $file_resize = [];
  public $file_link = [];
  public $editedMediaIndex = null;
  public $i;
  public $j;
  public $row = 1;
  public $externalmedia = false;
  public $initiate = false;
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

  public function mount(Product $product)
  {
    $this->product = $product;
    $this->columns = Schema::getColumnListing('media');
    $this->selectedColumns = $this->columns;
    $this->i = null;
    $this->j = null;
  }

  public function editMedia($index, $id)
  {
    $this->editedMediaIndex = $index;
    $media = Media::find($id);

    $this->filess = [
      $index . '.name' => $media->name,
      $index . '.type' => $media->type,
      $index . '.sequence' => $media->sequence,
    ];
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
      if (array_key_exists('type', $media_new)) {
        $media_for_prod->type = $media_new['type'];
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
    $this->chose = true;
  }
  public function external()
  {
    $this->row = 0;
    $this->externalmedia = true;
    $this->file_sequences[$this->row] = null;
    $this->file_link[$this->row] = null;
    $this->file_resize[$this->row] = false;
  }
  public function plus()
  {
    $this->row++;
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
      $this->checked = $this->product->media()->pluck('media.id')->map(fn($item) => (string) $item)->toArray();
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
  }
  public function clear($i)
  {
    array_splice($this->file_sequences, $i, 1);
    array_splice($this->file_link, $i, 1);
    array_splice($this->file_resize, $i, 1);
    $this->row--;

    if ($this->row < 0) {
      $this->externalmedia = false;
    }
  }
  public function saveexternal()
  {
    $productType = class_basename(get_class($this->product));
    $filespath = 'media/' . $productType . '/';
    $path = $filespath . $this->product->id . "/";


    if (!File::exists($filespath)) {
      File::makeDirectory($filespath, 0755, true);
    }
    if (!File::exists($filespath . $this->product->id)) {
      File::makeDirectory($filespath . $this->product->id, 0755, true);
    }


    for ($i = 0; $i <= $this->row; $i++) {
      $this->resetErrorBag();
      $this->validate([
        'file_sequences.*' => 'required',
        'file_link.*' => 'required|url'
      ]);

      $mediaLink = strtok($this->file_link[$i], '?');
      $mediaLink = preg_replace('/(_\d+x\d+)?(\.\w+)$/', '$2', $mediaLink);

      $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
      $fileExtension = strtolower(pathinfo($mediaLink, PATHINFO_EXTENSION));

      if (!in_array($fileExtension, $allowedExtensions)) {
        return;
      }
      $fileContent = file_get_contents($mediaLink);
      if ($fileContent == false) {
        return;
      }
      $imageInfo = getimagesizefromstring($fileContent);
      if (app()->has('global_auto_webp') &&  app('global_auto_webp') === 'true') {
        $image = Image::make($fileContent);
        $webpContent = $image->encode('webp')->__toString();
        $fileExtension = 'webp';

        $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->product->name)), '-');


        if (file_exists($path . $name . '.' . $fileExtension)) {
          $j = 1;
          while (file_exists($path . trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->product->name)), '-') . '(' . $j . ').' . $fileExtension)) {
            $j++;
          }
          $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->product->name)), '-') . '(' . $j . ').' . $fileExtension;
        }
        Storage::disk('media')->put($path . $name, $webpContent);
      } else {
        $fileExtension = image_type_to_extension($imageInfo[2], false);
        $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->product->name)), '-') . '.' . $fileExtension;
        if (file_exists($path . $name)) {
          $j = 1;
          while (file_exists($path . trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->product->name)), '-') . '(' . $j . ').' . $fileExtension)) {
            $j++;
          }
          $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->product->name)), '-') . '(' . $j . ').' . $fileExtension;
        }
        Storage::disk('media')->put($path . $name, $fileContent);
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

      // Handle resized versions
      if (!empty($this->file_resize[$i])) {
        if ($this->file_sequences[$i] == '1') {
          foreach (['min' => 70, 'main' => 300, 'full' => 640] as $typeKey => $resize) {
            $existing = $this->product->media()->where('type', $typeKey)->first();
            if ($existing) {
              $oldPath = $existing->path . $existing->name;
              if (Storage::disk('media')->exists($oldPath)) {
                Storage::disk('media')->delete($oldPath);
              }
              $existing->delete();
            }
            $this->resizeImage($file, $path, $resize, $typeKey, $name, $fileExtension,$this->file_sequences[$i]);
          }
        } else {
          $this->resizeImage($file, $path, 640, 'full', $name, $fileExtension,$this->file_sequences[$i]);
        }
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
    $this->chose = false;
    $this->mount($this->product);
  }
  private function resizeImage($file, $path, $size, $type, $name, $extension,$sequence)
  {
    $resizedImage = Image::make($file)
      ->resize($size, $size, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });

    $resizedName = "resized{$size}_" . $name;
    $resizedImage->encode('webp');

    Storage::disk('media')->put($path . $resizedName, (string) $resizedImage);

    $resizedMedia = new Media();
    $resizedMedia->path = $path;
    $resizedMedia->name = $resizedName;
    $resizedMedia->sequence = $sequence;
    $resizedMedia->extension = $extension;
    $resizedMedia->type = $type;
    $resizedMedia->width = $resizedImage->width();
    $resizedMedia->height = $resizedImage->height();
    $resizedMedia->size = Storage::disk('media')->size($path . $resizedName);
    $resizedMedia->createdby = Auth::user()->name;
    $resizedMedia->lastmodifiedby = Auth::user()->name;
    $resizedMedia->save();

    $this->product->media()->attach($resizedMedia->id);
  }


  public function save()
  {
    $productType = class_basename(get_class($this->product));
    $path = 'media/' . $productType . '/' . $this->product->id . '/';
    if (!File::exists($path)) {
      File::makeDirectory($path, 0755, true);
    }
    if (!File::exists($path . $this->product->id)) {
      File::makeDirectory($path . $this->product->id, 0755, true);
    }

    $this->i = 0;

    foreach ($this->medias as $file) {

      $type = (app()->has('global_auto_webp') && app('global_auto_webp') == 'true')
        ? 'webp'
        : $file->getClientOriginalExtension();

      $image = Image::make($file);
      $width = $image->width();
      $height = $image->height();

      $nameBase = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->product->name)), '-');
      $fileName = $nameBase . '.' . $type;

      $counter = 1;
      while (Storage::disk('media')->exists($path . $fileName)) {
        $fileName = "{$nameBase}({$counter}).{$type}";
        $counter++;
      }

      $isOriginal = $this->product->media()->where('type', 'original')->where('sequence', '1')->first();
      if ($this->file_sequences[$this->i] == '1' && $isOriginal) {
        $oldFile = $isOriginal->path . $isOriginal->name;
        if (Storage::disk('media')->exists($oldFile)) {
          Storage::disk('media')->delete($oldFile);
        }
        $isOriginal->delete();
      }

      $file->storeAs($path, $fileName, 'media');
      $media = new Media();
      $media->path = $path;
      $media->name = $fileName;
      $media->sequence = $this->file_sequences[$this->i];
      $media->type = 'original';
      $media->extension = $type;
      $media->width = $width;
      $media->height = $height;
      $media->size = $file->getSize();
      $media->createdby = Auth::user()->name;
      $media->lastmodifiedby = Auth::user()->name;
      $media->save();

      $this->product->media()->attach($media->id);

      if (!empty($this->file_resize[$this->i])) {
        if ($this->file_sequences[$this->i] == '1') {
          foreach (['min' => 70, 'main' => 300, 'full' => 640] as $typeKey => $resize) {
            $existing = $this->product->media()->where('type', $typeKey)->first();
            if ($existing) {
              $oldPath = $existing->path . $existing->name;
              if (Storage::disk('media')->exists($oldPath)) {
                Storage::disk('media')->delete($oldPath);
              }
              $existing->delete();
            }
            $this->resizeImage($file, $path, $resize, $typeKey, $fileName, $type, false, $this->file_sequences[$this->i]);
          }
        } else {
          $this->resizeImage($file, $path, 640, 'full', $fileName, $type, false, $this->file_sequences[$this->i]);
        }
      }
      $this->i++;
    }

    $this->medias = [];
    $this->initiate = false;
    $this->file_sequences = [];
    $this->file_resize = [];
    $this->chose = false;

    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
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
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->mount($this->product);
  }
  public function cancel_chose()
  {
    $this->chose = false;
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
    $this->multiple = false;
    session()->flash('notification', [
      'message' => 'Records related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);

    $this->mount($this->product);
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->product->media()->pluck('media.id')->map(fn($item) => (string) $item)->toArray();
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
    $query = $this->product->media();

    if (!empty($this->search)) {
      $search = $this->search;
      $query->where(function ($q) use ($search) {
        $columns = Schema::getColumnListing('media');

        foreach ($columns as $column) {
          if ($column === 'id' || $column === 'created_at' || $column === 'updated_at') {
            continue;
          }
          $q->orWhere($column, 'like', '%' . $search . '%');
        }
      });
    }

    $filteredMedia = $query->get();

    return view('livewire.related-media-product', [
      'filteredMedia' => $filteredMedia
    ]);
  }
}
