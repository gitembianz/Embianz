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
  public $timer = null;
  public $cookieConsent;
  public $advance =  false;
  public $ischecked = false;
  public function mount()
  {
    session(['fav_color' => 'green']);

    $this->cookieConsent = $this->checkCookieConsent();
    // dd(app()->make('promotions'));
    // if ($this->promotion) {
    //   if ($this->promotion->first()['cookieid'] && $this->promotion->first()['cookie_time']) {
    //     $promotionCookieId = $this->promotion->first()['cookieid'];

    //     $existingCookieId = request()->cookie('promotion_cookie_id');
    //     if (!$existingCookieId || $existingCookieId !== $promotionCookieId) {
    //       cookie()->queue('promotion_cookie_id', $promotionCookieId, 60 * $this->promotion->first()['cookie_time']);

    //       $this->timer = $this->promotion->first()['cooldown_timer'] ?? null;
    //     }
    //   }
    // }
  }


  // YTo2OntzOjY6Il90b2tlbiI7czo0MDoiczczSThOcmhrMDM1cTJ1OEpsbnRwdENKT1VvUVRoYnlEa1BxMGUyYyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjE0OiJodHRwOi8vZW1iaWFueiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo5OiJmYXZfY29sb3IiO3M6NToiZ3JlZW4iO30=

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
          '00N9N000000PrL5' => config('app.url'),
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
    setrawcookie('cookieConsent', 'accepted');
    $this->emit('updateCookieConsent');
  }
  public function getPromotionProperty()
  {
    if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {

      return collect(app()->make('promotions'))
        ->filter(function ($promotion) {
          return isset($promotion['start_date'], $promotion['end_date'], $promotion['type']) && // Ensure keys exist
            $promotion['start_date'] <= now()->format('Y-m-d') &&
            $promotion['end_date'] >= now()->format('Y-m-d') &&
            $promotion['type'] === 'counter';
        });
    } else {
      return collect();
    }
  }
}
