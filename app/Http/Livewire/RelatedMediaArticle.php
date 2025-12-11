<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class RelatedMediaArticle extends Component
{

  use WithFileUploads;
  use WithPagination;
  public $article;
  public $showmedia = false;
  public $type;
  public $media = null;
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
  public $file_link;
  public $file_name;
  public $editedMediaIndex = null;
  public $i;
  public $j;
  public $externalmedia = false;
  public $initiate = false;
  public $chose = false;

  public $single = false;
  public $multiple = false;
  public $rind = null;
  public $rind2 = null;
  public $rind3 = null;


  public function render()
  {
    $query = $this->article->media();

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

    return view('livewire.related-media-article', [
      'filteredMedia' => $filteredMedia
    ]);
  }

  public function mount(Article $article)
  {
    $this->article = $article;
    $this->columns = Schema::getColumnListing('media');
    $this->selectedColumns = $this->columns;
    $this->i = null;
    $this->j = null;
  }

  // expand functions
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

  public function editMedia($index, $id)
  {
    $this->editedMediaIndex = $index;
    $media = Media::find($id);

    $this->filess = [
      $index . '.name' => $media->name,
      $index . '.type' => $media->type,
    ];
  }
  public function cancelMedia()
  {
    $this->editedMediaIndex = null;
    $this->filess = [];
  }
  public function saveMedia($mediaIndex, $id)
  {
    $record = $this->filess[$mediaIndex] ?? NULL;
    if (!is_null($record)) {
      $media = Media::find($id);

      if (array_key_exists('name', $record)) {
        $newName = $record['name'] . '.' . $media->extension;
        $oldName = $media->name;
        if ($newName !== $oldName) {
          $path = $media->path;
          if (file_exists($path . $newName)) {
            $i = 1;
            while (file_exists($path . $record['name'] . '(' . $i . ').' . $media->extension)) {
              $i++;
            }
            $newName = $record['name'] . '(' . $i . ').' . $media->extension;
          }
          $oldFilePath = $path . $oldName;
          $newFilePath = $path . $newName;
          $media->name = $newName;
          $media->save();
          if (file_exists($oldFilePath)) {
            rename($oldFilePath, $newFilePath);
          }
        }
      }
      $media->save();
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
    $this->externalmedia = true;
    $this->article = null;
    $this->file_link = null;
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->article->media()->pluck('media.id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
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

  public function deleteRecords()
{
    $medias = Media::whereKey($this->checked)->get();
    foreach ($medias as $media) {
        $path = $media->path . $media->name;
        if (\App\Helpers\MediaHelper::exists($path)) {
            \App\Helpers\MediaHelper::delete($path);
        }
        
        $media->delete();
        
        $folder = public_path($media->path);
        if (is_dir($folder)) {
            $files = array_diff(scandir($folder), ['.', '..']);
            if (empty($files)) {
                @rmdir($folder);
            }
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

  public function deleteSingleRecord()
{
    $media = Media::findOrFail($this->idbeingremoved);
    $path = $media->path . $media->name;
    if (\App\Helpers\MediaHelper::exists($path)) {
        \App\Helpers\MediaHelper::delete($path);
    }
    
    $media->delete();
    
    $folder = public_path($media->path);
    if (is_dir($folder)) {
        $files = array_diff(scandir($folder), ['.', '..']);
        if (empty($files)) {
            @rmdir($folder);
        }
    }
    
    $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
    $this->single = false;
    
    session()->flash('notification', [
        'message' => 'Record deleted successfully!',
        'type' => 'success',
        'title' => 'Success'
    ]);
}
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->article->media()->pluck('media.id')->map(fn($item) => (string) $item)->toArray();
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

  public function clearall()
  {
    $this->externalmedia = false;
    $this->file_link = null;
  }

  public function closeModalLogo()
  {
    $this->chose = false;
    $this->media = null;
    $this->externalmedia = false;
  }

private function resizeImage($file, $path, $size, $type, $name, $extension, $external)
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

    $resizedImage->encode('webp')->save(public_path($path . "resized{$size}_" . $name));
    
    $resizedMedia = new Media();
    $resizedMedia->path = $path;
    $resizedMedia->name = "resized{$size}_" . $name;
    $resizedMedia->extension = $extension;
    $resizedMedia->type = $type;
    $resizedMedia->width = $resizedImage->width();
    $resizedMedia->height = $resizedImage->height();
    $resizedMedia->size = \App\Helpers\MediaHelper::size($path . "resized{$size}_" . $name);
    $resizedMedia->createdby = Auth::user()->name;
    $resizedMedia->lastmodifiedby = Auth::user()->name;
    $resizedMedia->save();
    $this->article->media()->attach($resizedMedia->id);
}


public function saveexternal()
{
    $itemType = class_basename(get_class($this->article));
    $filespath = 'media/' . $itemType . '/';
    
    if (!\App\Helpers\MediaHelper::exists($filespath)) {
        File::makeDirectory(public_path($filespath), 0755, true);
    }
    
    if (!\App\Helpers\MediaHelper::exists($filespath . $this->article->id)) {
        File::makeDirectory(public_path($filespath . $this->article->id), 0755, true);
    }
    $path = $filespath . $this->article->id . "/";

    $this->resetErrorBag();
    $this->validate([
      'file_link.*' => 'required|url'
    ]);

    $mediaLink = strtok($this->file_link, '?');
    $this->file_link = preg_replace('/(_\d+x\d+)?(\.\w+)$/', '$2', $mediaLink);

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    $fileExtension = strtolower(pathinfo($this->file_link, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
      session()->flash('notification', [
        'message' => 'File extension not allowed!',
        'type' => 'warning',
        'title' => 'warning'
      ]);
      return;
    }
    $fileContent = file_get_contents($this->file_link);
    if ($fileContent == false) {
      session()->flash('notification', [
        'message' => 'File not found at link provided!',
        'type' => 'warning',
        'title' => 'warning'
      ]);
      return;
    }

    $imageInfo = getimagesizefromstring($fileContent);

    if (app()->has('global_auto_webp') &&  app('global_auto_webp') === 'true') {

      $image = Image::make($fileContent);
      $webpContent = $image->encode('webp')->__toString();
      $fileExtension = 'webp';

      $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->article->name)), '-');

      if (file_exists($path . $name . '.' . $fileExtension)) {
        $j = 1;
        while (file_exists($path . trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->article->name)), '-') . '(' . $j . ').' . $fileExtension)) {
          $j++;
        }
        $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->article->name)), '-') . '(' . $j . ').' . $fileExtension;
      }

      \App\Helpers\MediaHelper::put($path . $name, $webpContent);
    } else {

      $fileExtension = image_type_to_extension($imageInfo[2], false);

      $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->article->name)), '-');

      if (file_exists($path . $name . '.' . $fileExtension)) {
        $j = 1;
        while (file_exists($path . trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->article->name)), '-') . '(' . $j . ').' . $fileExtension)) {
          $j++;
        }
        $name = trim(strtolower(preg_replace('/[^a-z0-9]+/i', '--', $this->article->name)), '-') . '(' . $j . ').' . $fileExtension;
      }
      \App\Helpers\MediaHelper::put($path . $name, $fileContent);
    }
    $isoriginal = $this->article->media()->where('type', 'original')->first();
    if ($isoriginal) {
      $isoriginal->delete();
    }

    $media = new Media();
    $media->name = $name;
    $media->extension = $fileExtension;
    $media->width = $imageInfo[0];
    $media->height =  $imageInfo[1];
    $media->size = strlen($fileContent);
    $media->type = 'original';
    $media->path = $path;
    $media->createdby = Auth::user()->name;
    $media->lastmodifiedby = Auth::user()->name;
    $media->save();
    $this->article->media()->attach($media->id);

    //Resize system
    $filePath = $path . $name;
    $file = \App\Helpers\MediaHelper::get($filePath);
    // min image(70x70)
    $ismin = $this->article->media()->where('type', 'min')->first();
    if ($ismin) {
      $oldPath = $ismin->path . $ismin->name;
      if (\App\Helpers\MediaHelper::exists($oldPath)) {
        \App\Helpers\MediaHelper::delete($oldPath);
      }
      $ismin->delete();
    }
    $this->resizeImage($file, $path, 70, 'min', $name, $fileExtension, true);

    //main image(350x3500)
    $ismain = $this->article->media()->where('type', 'main')->first();
    if ($ismain) {
      $oldPath = $ismain->path . $ismain->name;
      if (\App\Helpers\MediaHelper::exists($oldPath)) {
        \App\Helpers\MediaHelper::delete($oldPath);
      }
      $ismain->delete();
    }
    $this->resizeImage($file, $path, 350, 'main', $name, $fileExtension, true);

    //full image(640x640)
    $isfull = $this->article->media()->where('type', 'full')->first();
    if ($isfull) {
      $oldPath = $isfull->path . $isfull->name;
      if (\App\Helpers\MediaHelper::exists($oldPath)) {
        \App\Helpers\MediaHelper::delete($oldPath);
      }
      $isfull->delete();
    }
    $this->resizeImage($file, $path, 640, 'full', $name, $fileExtension, true);

    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->externalmedia = false;
    $this->file_link = null;
    $this->chose = false;
    $this->mount($this->article);
  }

 public function save()
{
    $itemType = class_basename(get_class($this->article));
    $filespath = 'media/' . $itemType . '/';
    if (!\App\Helpers\MediaHelper::exists($filespath)) {
        File::makeDirectory(public_path($filespath), 0755, true);
    }
    if (!\App\Helpers\MediaHelper::exists($filespath . $this->article->id)) {
        File::makeDirectory(public_path($filespath . $this->article->id), 0755, true);
    }
    $path = $filespath . $this->article->id . "/";

    //media handdleer

    $file = $this->media[0];

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

    $media->name = strtolower(preg_replace('/\s+/', '-', $filename)) . '.' . $type;
    if (file_exists($path . $media->name)) {
      $this->j = 1;
      while (file_exists($path . strtolower(preg_replace('/\s+/', '-', $filename)) . '(' . $this->j . ').' . $type)) {
        $this->j++;
      }
      $media->name = strtolower(preg_replace('/\s+/', '-', $filename)) . '(' . $this->j . ').' . $type;
    }

    $isoriginal = $this->article->media()->where('type', 'original')->first();
    if ($isoriginal) {
      $oldPath = $isoriginal->path . $isoriginal->name;
      if (\App\Helpers\MediaHelper::exists($oldPath)) {
        \App\Helpers\MediaHelper::delete($oldPath);
      }
      $isoriginal->delete();
    }
    $file->storeAs($path, $media->name, 'public_upload');
    $media->type = 'original';
    $media->extension = $type;
    $media->width = $width;
    $media->height =  $height;
    $media->size = $file->getSize();
    $media->createdby = Auth::user()->name;
    $media->lastmodifiedby = Auth::user()->name;
    $media->save();
    $this->article->media()->attach($media->id);

    // min image(70x70)
    $ismin = $this->article->media()->where('type', 'min')->first();
    if ($ismin) {
      $oldPath = $ismin->path . $ismin->name;
      if (\App\Helpers\MediaHelper::exists($oldPath)) {
        \App\Helpers\MediaHelper::delete($oldPath);
      }
      $ismin->delete();
    }
    $this->resizeImage($file, $path, 70, 'min', $media->name, $media->extension, false);
    //main image(350x3500)
    $ismain = $this->article->media()->where('type', 'main')->first();
    if ($ismain) {
      $oldPath = $ismain->path . $ismain->name;
      if (\App\Helpers\MediaHelper::exists($oldPath)) {
        \App\Helpers\MediaHelper::delete($oldPath);
      }
      $ismain->delete();
    }
    $this->resizeImage($file, $path, 350, 'main', $media->name, $media->extension, false);

    //full image(640x640)
    $isfull = $this->article->media()->where('type', 'full')->first();
    if ($isfull) {
      $oldPath = $isfull->path . $isfull->name;
      if (\App\Helpers\MediaHelper::exists($oldPath)) {
        \App\Helpers\MediaHelper::delete($oldPath);
      }
      $isfull->delete();
    }
    $this->resizeImage($file, $path, 640, 'full', $media->name, $media->extension, false);

    $this->media = null;
    $this->initiate = false;
    $this->chose = false;
    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  public function cancel()
  {
    $this->media = null;
  }
  // de sters prin $set in blade
  public function cancel_chose()
  {
    $this->chose = false;
  }
}
