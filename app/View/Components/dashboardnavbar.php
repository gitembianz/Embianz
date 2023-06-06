<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class dashboardnavbar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $client =Auth::user()->id;
        $client = [1,3];
        //$bg="color: red";
        //add '$bg' to make it work!
        return view('components.dashboardnavbar')->with('user', 'test');
    }
}
