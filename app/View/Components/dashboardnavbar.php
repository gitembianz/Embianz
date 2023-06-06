<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class dashboardnavbar extends Component
{
    /**
     * Create a new component instance.
     *
     *
     * @return void
     */

     public $user;

    public function __construct($user)
    {
      $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        //$bg="color: red";
        //add '$bg' to make it work!
        return view('components.dashboardnavbar');
    }
}
