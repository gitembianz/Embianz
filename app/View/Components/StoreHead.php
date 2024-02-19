<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
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
            ? "Noren.ro is a website dedicated to providing eco-friendly products for a sustainable lifestyle. Shop our wide range of environmentally-friendly products including reusable items, zero-waste essentials, and more."
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
