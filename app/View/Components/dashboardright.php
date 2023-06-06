<?php

namespace App\View\Components;

use App\Models\Todolist;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Http;

class dashboardright extends Component
{

 // public $todolists;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
      //   $this->todolists = $todolists;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $todolists = Todolist::all();
        return Http::get(view('components.dashboardright', compact('todolists')));
    }
}
