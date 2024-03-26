<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StoreHead extends Component
{
    public $title;
    public $description;
    public $canonical;

    /**
     * Create a new component instance.
     */
    public function __construct($title = "", $description = "", $canonical = "")
    {
        $this->title = $title . app('global_site_name');
        $this->description = empty($description)
            ? "Toate Produsele"
            : $description;
        $this->canonical = $canonical;
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
