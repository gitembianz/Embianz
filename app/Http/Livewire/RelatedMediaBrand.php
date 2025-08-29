<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class RelatedMediaBrand extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $chose = false;

    public $delete = false;
    public $rind = null;
    public $rind2 = null;
    public $rind3 = null;
    public $i;
    public $j;
    public $brand;
    public $columns = [];
    public $selectedColumns = [];
    public $showmedia = false;
    public $row;
    public $externalmedia = false;
    public $file_link = [];
    public $file_name = [];
    public $editedMediaIndex = null;
    public $filess = [];
    public $medias = [];
    public $idbeingremoved = null;





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


    public function render()
    {
        $filteredMedia = $this->brand->media()->get();
        return view('livewire.related-media-brand', [
            'filteredMedia' => $filteredMedia
        ]);
    }
    public function mount(Brand $brand)
    {
        $this->brand = $brand;
        $this->columns = Schema::getColumnListing('media');
        $this->selectedColumns = $this->columns;
        $this->i = null;
        $this->j = null;
    }
    public function uploadmedia()
    {
        $this->showmedia = true;
        $this->chose = true;
    }
    public function clearall()
    {
        $this->row = 0;
        $this->externalmedia = false;
        $this->file_link = [];
        $this->file_name = [];
        $this->chose = false;
    }
    public function external()
    {
        $this->row = 0;
        $this->externalmedia = true;
        $this->file_name[$this->row] = null;
        $this->file_link[$this->row] = null;
    }
    public function saveexternal()
    {
        $productType = class_basename(get_class($this->brand));
        $filespath = 'media/' . $productType . '/';
        if (!File::exists($filespath)) {
            File::makeDirectory($filespath, 0755, true);
        }
        if (!File::exists($filespath . $this->brand->id)) {
            File::makeDirectory($filespath . $this->brand->id, 0755, true);
        }
        $path = $filespath . $this->brand->id . "/";

        for ($this->i = 0; $this->i <= $this->row; $this->i++) {
            $this->resetErrorBag();
            $this->validate([
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

            if (app()->has('global_brand_media_dimension')) {
                $dimension = (int) app('global_brand_media_dimension');
            } else {
                $dimension = 100;
            }
            $ismedia = $this->brand->media()->first();
            if ($ismedia) {
                if (File::exists($ismedia->path . $ismedia->name)) {
                    File::delete($ismedia->path . $ismedia->name);
                }
                $resizedImage = Image::make($file)
                    ->resize($dimension, $dimension, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $newPath = $ismedia->path . $name;
                $resizedImage->encode('webp')->save($newPath);
                $ismedia->path = $ismedia->path;
                $ismedia->name =  $name;
                $ismedia->extension = $fileExtension;
                $ismedia->width = $resizedImage->width();
                $ismedia->height = $resizedImage->height();
                $ismedia->size = File::size($newPath);
                $ismedia->lastmodifiedby = Auth::user()->name;
                $ismedia->save();
            } else {

                $resizedImage = Image::make($file)
                    ->resize($dimension, $dimension, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $resizedImage->encode('webp')->save($path . $name);
                $media = new Media();
                $media->path = $path;
                $media->name = $name;
                $media->extension = $fileExtension;
                $media->width = $resizedImage->width();
                $media->height = $resizedImage->height();
                $media->type = 'n/a';
                $media->size = strlen($fileContent);
                $media->createdby = Auth::user()->name;
                $media->lastmodifiedby = Auth::user()->name;
                $media->save();
                $this->brand->media()->attach($media->id);
            }
        }
        session()->flash('notification', [
            'message' => 'Record related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->row = 0;
        $this->externalmedia = false;
        $this->file_link = [];
        $this->file_name = [];
        $this->chose = false;
    }
    public function editMedia($index, $id)
    {
        $this->editedMediaIndex = $index;
        $media = Media::find($id);

        $this->filess = [
            $index . '.name' => $media->name
        ];
    }
    public function cancel()
    {
        $this->medias = [];
        $this->chose = false;
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
            $medtoedit = Media::find($id);
            if (array_key_exists('name', $media_new)) {
                $newName = $media_new['name'] . '.' . $medtoedit->extension;
                $oldName = $medtoedit->name;
                if ($newName !== $oldName) {
                    $path = $medtoedit->path;
                    if (file_exists($path . $newName)) {
                        $i = 1;
                        while (file_exists($path . $media_new['name'] . '(' . $i . ').' . $medtoedit->extension)) {
                            $i++;
                        }
                        $newName = $media_new['name'] . '(' . $i . ').' . $medtoedit->extension;
                    }
                    $oldFilePath = $path . $oldName;
                    $newFilePath = $path . $newName;
                    $medtoedit->name = $newName;
                    $medtoedit->save();
                    if (file_exists($oldFilePath)) {
                        rename($oldFilePath, $newFilePath);
                    }
                }
            }
            $medtoedit->save();
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
    public function savelocal()
    {
        $productType = class_basename(get_class($this->brand));
        $filespath = 'media/' . $productType . '/';
        if (!File::exists($filespath)) {
            File::makeDirectory($filespath, 0755, true);
        }
        if (!File::exists($filespath . $this->brand->id)) {
            File::makeDirectory($filespath . $this->brand->id, 0755, true);
        }
        $path = $filespath . $this->brand->id . "/";

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
            if (app()->has('global_brand_media_dimension')) {
                $dimension = (int) app('global_brand_media_dimension');
            } else {
                $dimension = 100;
            }
            $ismedia = $this->brand->media()->first();
            if ($ismedia) {
                if (File::exists($ismedia->path . $ismedia->name)) {
                    File::delete($ismedia->path . $ismedia->name);
                }

                $resizedImage = Image::make($file->getRealPath())
                    ->resize($dimension, $dimension, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                $newPath = $ismedia->path  . $name;
                $resizedImage->encode('webp')->save($newPath);
                $ismedia->path = $ismedia->path;
                $ismedia->name = $name;
                $ismedia->sequence = $this->file_sequences[$index];
                $ismedia->extension = $type;
                $ismedia->width = $resizedImage->width();
                $ismedia->height = $resizedImage->height();
                $ismedia->size = File::size($newPath);
                $ismedia->lastmodifiedby = Auth::user()->name;
                $ismedia->save();
            } else {
                $resizedImage = Image::make($file->getRealPath())
                    ->resize($dimension, $dimension, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                $resizedImage->encode('webp')->save($path . $name);
                $media = new Media();
                $media->path = $path;
                $media->name = $name;
                $media->extension = $type;
                $media->width = $resizedImage->width();
                $media->height = $resizedImage->height();
                $media->type = 'n/a';
                $media->size = $file->getSize();
                $media->createdby = Auth::user()->name;
                $media->lastmodifiedby = Auth::user()->name;
                $media->save();
                $this->brand->media()->attach($media->id);
            }
        }
        $this->medias = [];
        $this->chose = false;
        session()->flash('notification', [
            'message' => 'Record related successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
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
        $this->delete = false;
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

    public function confirmItemRemoval($id)
    {
        $this->idbeingremoved = $id;
        $this->delete = true;
    }

    public function cancel_delete()
    {
        $this->delete = false;
    }
}
