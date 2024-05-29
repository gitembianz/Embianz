<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StoreHead extends Component
{
    public $title;
    public $description;
    public $canonical;
    public $image;

    /**
     * Create a new component instance.
     */
    public function __construct($title = "", $description = "", $canonical = "", $image = "")
    {
        $this->title = $title . app('global_site_name');
        $this->description = empty($description)
            ? ""
            : $description;
        $this->canonical = $canonical;
        $this->image  = empty($image)
            ? "images/store/logo-banner.webp"
            : $image;
    }

    public function render()
    {
        return view('components.store-head', [
            'title' => $this->title,
            'description' => $this->description,
            'canonical' => $this->canonical
        ]);
    }
}
