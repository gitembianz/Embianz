<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Subscribers;
use Illuminate\Database\QueryException;

class StoreFooter extends Component
{

  public $email = null;
  public $ischecked = false;
  public $session_id;
  public $timer = 0;
  public $page;
  public $staticpages;

  public function render()
  {
    return view('livewire.store-footer');
  }
  public function mount($page = "")
  {
    $this->staticpages = collect(app('static_pages'))->where('display_in_footer', true)->values();
    $this->session_id = request()->cookie('sessionId') ?? session()->getId();


    $this->page = $page;
    if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {
     $this->timer = app('promotionService')->getRemainingTime($this->session_id);
    }
  }

  public function timmerexpired()
  {
    $this->timer = 0;
    // $this->emit('timmerexpired');
    $this->dispatchBrowserEvent('refresh-page');
  }

  public function store()
  {
    $this->resetErrorBag();

    try {
      $validatedData = $this->validate([
        'email' => 'required|email',
      ]);

      $sucscriber = Subscribers::create($validatedData);

      $this->reset();
      $this->dispatchBrowserEvent('newsletterToggle');
    } catch (QueryException $e) {
      if ($e->errorInfo[1] === 1062) {
        $this->reset();
        $this->dispatchBrowserEvent('newsletterToggle');
      } else {
      }
    }
  }
}
