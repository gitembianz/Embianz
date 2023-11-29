<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subscribers;

class StoreFooter extends Component
{

  public $email;
  public $response = null;
  public $cookieConsent;
  public $cookieId;
  public function mount()
  {
    $this->cookieId = $this->getCookieSessionId();

    if (!$this->cookieId) {
      $this->saveSessionId();
    }
    $this->cookieConsent = $this->checkCookieConsent();
  }
  private function getCookieSessionId()
  {
    return request()->cookie('sessionId');
  }

  public function render()
  {
    return view('livewire.store-footer');
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

  public function saveSessionId()
  {
    $sessionId = session()->getId();
    return response('')->cookie('sessionId', $sessionId, 30 * 24 * 60 * 60);
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
    return response('')->cookie('cookieConsent', 'accepted', 30 * 24 * 60 * 60);
  }
}
