<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StoreHeader extends Component
{
  public $session_id;
  public $timer = null;
  public $staticpages;

  private function getSessionId()
  {
    $cookieSessionId = request()->cookie('sessionId');
    $sessionId = $cookieSessionId ?: session()->getId();
    $period = app()->has('global_cookie_max_ages') ? app('global_cookie_max_ages') : 30;
    cookie()->queue(cookie()->make('sessionId', $sessionId, 60 * 24 * $period));
    return $sessionId;
  }

  public function render()
  {
    $data = [
      'categories' => $this->categories

    ];
    return view('livewire.store-header', $data);
  }

  public function mount()
  {
    $this->session_id = $this->getSessionId();
    $this->staticpages = collect(app('static_pages'))->where('display_in_footer', true)->values();
  }

  public function getCategoriesProperty()
  {
    return collect(resolve(\App\Services\CategoryService::class)->getHeader());
  }
}
