<?php

namespace App\View\Components;

use App\Models\Todolist;
use Illuminate\View\Component;

class dashboardright extends Component
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
        $todolists = Todolist::all();
        $content = $todolists->first();
        $content = $content->content;
        return view('components.dashboardright', compact('content'));
    }
}
