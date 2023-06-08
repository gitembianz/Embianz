<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Dashboardnavbar extends Component
{
    /**
     * Create a new component instance.
     *
     *
     * @return void
     */

    public function __construct()
    {
      //test
    }


    public function render()
    {
        $user = Auth::user()->name;
        //$bg="color: red";
        //add '$bg' to make it work!

        return view('components.dashboardnavbar', compact('user'));
    }
}
