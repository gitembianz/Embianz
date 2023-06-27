<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class RelatedMediaProduct extends Component
{

  use WithFileUploads;
  use WithPagination;

    public function render()
    {
        return view('livewire.related-media-product');
    }
}
