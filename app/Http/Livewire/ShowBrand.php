<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use Livewire\Component;
use Illuminate\Support\Facades\File;


class ShowBrand extends Component
{
    public $delete = false;
    public $edit = null;
    public $item;
    public $brandid;
    public function render()
    {
        return view('livewire.show-brand', ['brand' => $this->brand]);
    }
    public function mount($id)
    {
        $this->brandid = $id;
    }
    public function getBrandProperty()
    {
        return Brand::find($this->brandid);
    }
    public function edit()
    {
        $this->item = [
            'name' => $this->brand->name,
            'description' => $this->brand->description,
        ];
        $this->edit = true;
    }
    public function canceledit()
    {
        $this->edit = null;
        $this->item = [];
    }
    public function updated()
    {
        $this->dispatchBrowserEvent('tabNavigation');
    }
    public function saveedit()
    {
        $updated = $this->item ?? NULL;
        if (!is_null($updated)) {
            if (array_key_exists('name', $updated)) {
                $this->brand->name = $updated['name'];
            }
            if (array_key_exists('description', $updated)) {
                $this->brand->description = $updated['description'];
            }
            $this->brand->save();
        }
        $this->emit('itemSaved');
        session()->flash('notification', [
            'message' => 'Record edited successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
        $this->edit = null;
        $this->item = [];
    }
    public function cancelItemRemoval()
    {
        $this->delete = false;
    }
    public function confirmRemoval()
    {
        $this->delete = true;
    }
    public function deleteRecord()
    {
        $item = Brand::findOrFail($this->brandid);
        $medias = $item->media()->get();
        foreach ($medias as $media) {
            $media->delete();
        }
        $productType = class_basename(get_class($item));
        $filespath = 'media/' . $productType . '/' . $item->id;
        if (File::exists($filespath)) {
            File::deleteDirectory($filespath);
        }
        $item->delete();
        $this->delete = false;

        return redirect()->route('all_brands')->with('notification', [
            'message' => 'Record deleted successfully!',
            'type' => 'success',
            'title' => 'Success'
        ]);
    }
}