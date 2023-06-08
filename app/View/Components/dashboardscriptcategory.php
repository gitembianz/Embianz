<?php

namespace App\View\Components;

use App\Models\Category;
use Illuminate\View\Component;

class Dashboardscriptcategory extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //test
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $categories = Category::pluck('name');
        return view('components.dashboardscriptcategory', compact('categories'));
    }
}
