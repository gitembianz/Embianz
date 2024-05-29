<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Subscribers;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;

class StoreFooter extends Component
{

  public $email = null;
  public $response = null;
  public $cookieConsent;
  public $advance =  false;
  public $ischecked = false;
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

    try {
      $validatedData = $this->validate([
        'email' => 'required|email',
      ]);

      $sucscriber = Subscribers::create($validatedData);

      $client = new Client();
      $client->post('https://webto.salesforce.com/servlet/servlet.WebToLead', [
        'headers' => [
          'Accept' => 'application/json',
        ],
        'query' => [
          'oid' => '00D09000008XPQu',
          '00N9N000000PrL5' => 'www.noren.ro',
          'lead_source' => 'Web',
          'email' => $sucscriber->email,
        ],
        'curl' => [
          CURLOPT_SSL_VERIFYPEER => false,
        ],
      ]);

      $this->reset();
      $this->cookieConsent = $this->checkCookieConsent();
      $this->dispatchBrowserEvent('newsletterToggle');
    } catch (QueryException $e) {
      if ($e->errorInfo[1] === 1062) {
        $this->reset();
        $this->cookieConsent = $this->checkCookieConsent();
        $this->dispatchBrowserEvent('newsletterToggle');
      } else {
      }
    }
  }

  private function checkCookieConsent()
  {
    if (isset($_COOKIE['cookieConsent']) && $_COOKIE['cookieConsent'] == 'accepted') {
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
