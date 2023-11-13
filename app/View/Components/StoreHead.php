<?php

namespace App\View\Components;

use App\Models\Store_Settings;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StoreHead extends Component
{
    public $title;
    /**
     * Create a new component instance.
     */
    public function __construct($title = "")
    {
        $this->title = Store_Settings::where('parameter', 'site_name')->first()->value . $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.store-head', ['title' => $this->title]);
    }
}
