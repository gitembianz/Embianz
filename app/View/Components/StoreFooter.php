<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StoreFooter extends Component
{
    public $page;

    /**
     * Create a new component instance.
     */
    public function __construct($page = "",)
    {
        $this->page = $page;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.store-footer', [
            'page' => $this->page
        ]);
    }
}