<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Subscribers;

class StoreFooter extends Component
{

  public $email;
  public $response = null;
  public $cookieConsent;
  public $advance =  false;
  public function mount()
  {
    $this->cookieConsent = $this->checkCookieConsent();
  }

  public function render()
  {
    return view('livewire.store-footer');
  }
  public function advancecookie()
  {
    $this->advance = !$this->advance;
  }
  public function store()
  {
    $this->resetErrorBag();
    $validatedData = $this->validate([
      'email' => 'required|email'
    ]);
    Subscribers::create($validatedData);
    $this->reset();
    session()->flash('notification', [
      'message' => 'Thank you for subscription!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }

  private function checkCookieConsent()
  {
    if (isset($_COOKIE['cookieConsent']) && $_COOKIE['cookieConsent'] === 'accepted') {
      return true;
    }
    return false;
  }
  public function acceptCookie()
  {
    $this->cookieConsent = true;
    setcookie('cookieConsent', 'accepted', time() + (30 * 24 * 60 * 60), '/');
    $this->emit('updateCookieConsent');
  }
}
