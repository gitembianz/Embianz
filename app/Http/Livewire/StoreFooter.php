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
    // Check if the cookie has been accepted
    $this->cookieConsent = $this->checkCookieConsent();
    $this->cookieId = $this->getCookieId();

    if (!$this->cookieId) {
      $this->saveSessionId();
    }
    // if (!$this->cookieConsent) {
    //   $this->acceptCookie();
    // }
  }

  public function render()
  {
    return view('livewire.store-footer', [
      'categories' => $this->categories
    ]);
  }

  public function acceptCookie()
  {
    $this->cookieConsent = true;
    setcookie('cookieConsent', 'accepted', time() + (30 * 24 * 60 * 60), '/');
    $this->emit('updateCookieConsent');
  }

  private function checkCookieConsent()
  {
    if (isset($_COOKIE['cookieConsent']) && $_COOKIE['cookieConsent'] === 'accepted') {
      return true;
    }
    return false;
  }

  private function getCookieId()
  {
    if (isset($_COOKIE['sessionId'])) {
      return $_COOKIE['sessionId'];
    }
    return null;
  }

  private function saveSessionId()
  {
    $sessionId = session()->getId();
    setcookie('sessionId', $sessionId, time() + (30 * 24 * 60 * 60), '/');
    $this->emit('updateCookieConsent', $sessionId);
    $this->cookieId = true;
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
  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->limit($this->limit)->get()->pluck('name', 'id');
  }
  public function getCategoriesQueryProperty()
  {
    return Category::orderBy('sequence', 'asc');
  }
}