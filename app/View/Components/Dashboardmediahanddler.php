<?php

namespace App\View\Components;

use App\Models\MediaLocation;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dashboardmediahanddler extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //test
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $medialocations = MediaLocation::all();
        return view('components.dashboardmediahanddler', compact('medialocations'));
    }
}
