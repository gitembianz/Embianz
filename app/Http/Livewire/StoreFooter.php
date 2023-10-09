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

  public function mount()
  {
    // Check if the cookie has been accepted
    $this->cookieConsent = $this->checkCookieConsent();
  }


  public function render()
  {
    return view('livewire.store-footer', [
      'categories' => $this->categories
    ]);
  }
  public function acceptCookie()
  {
    $sessionId = session()->getId();
    $this->cookieConsent = true;
    $data = [
      'sessionId' => $sessionId,
      'cookieConsent' => 'accepted',
    ];
    setcookie('cookieConsentData', json_encode($data), time() + (7 * 24 * 60 * 60), '/');
    $this->emit('updateCookieConsent', $sessionId);
  }

  private function checkCookieConsent()
  {
    if (isset($_COOKIE['cookieConsentData'])) {
      $cookieData = json_decode($_COOKIE['cookieConsentData'], true);
      if (isset($cookieData['cookieConsent']) && $cookieData['cookieConsent'] === 'accepted') {
        return true; // Cookie consent is accepted
      }
    }
    return false; // Cookie consent is not accepted
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
