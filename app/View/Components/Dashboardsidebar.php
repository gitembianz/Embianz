<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dashboardsidebar extends Component
{
  public $active;

  public function __construct($active = "")
  {
    $this->active = $active;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render()
  {
    return view('components.dashboardsidebar', ['active' => $this->active]);
  }
}