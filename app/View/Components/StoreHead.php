<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StoreHead extends Component
{
    public $title;
    public $description;
    public $canonical;
    public $image;
    public $preload;

    /**
     * Create a new component instance.
     */
    public function __construct($title = "", $description = "", $canonical = "", $image = "", $preload = "")
    {
        $this->title = $title ?? app('global_site_name');

        $this->description = empty($description) ? "" : $description;

        $this->canonical = empty($canonical)
            ? url()->current() . (request()->getQueryString() ? '?' . request()->getQueryString() : '')
            : config('app.url') . '/' . ltrim($canonical, '/');


        $this->image = empty($image) ? "images/store/logo-banner.webp" : $image;

        $this->preload = $preload;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('components.store-head', [
            'title' => $this->title,
            'description' => $this->description,
            'canonical' => $this->canonical,
            'image' => $this->image,
            'preload' => $this->preload,
        ]);
    }
}
