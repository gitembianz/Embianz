<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subscribers;

class StoreFooter extends Component
{

  public $email;
  public $response = null;
  public $limit = 5;
  public $cookieConsent;
  public $cookieId;
  public function mount()
  {
    $this->cookieId = $this->getCookieId();

    if (!$this->cookieId) {
      $this->saveSessionId();
    }
    $this->cookieConsent = $this->checkCookieConsent();
  }
  private function getCookieId()
  {
    if (isset($_COOKIE['sessionId'])) {
      return $_COOKIE['sessionId'];
    }
    return null;
  }

  public function render()
  {
    return view('livewire.store-footer', [
      'categories' => $this->categories
    ]);
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

  private function saveSessionId()
  {
    $sessionId = session()->getId();
    setcookie('sessionId', $sessionId, time() + (30 * 24 * 60 * 60), '/');
    $this->emit('updateCookieConsent', $sessionId);
    $this->cookieId = true;
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
  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->limit($this->limit)->get()->pluck('name', 'id');
  }
  public function getCategoriesQueryProperty()
  {
    return Category::orderBy('sequence', 'asc');
  }
}
