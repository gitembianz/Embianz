<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Livewire\Component;
use App\Models\Subscribers;
use App\Models\Static_Page;
use App\Models\UserSessions;
use Illuminate\Support\Facades\Cache;
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
    $this->staticpages = Cache::rememberForever('static_pages', function () {
      return Static_Page::where('display_in_footer', true)->get();
    });
    $this->page = $page;
    if (app()->has('global_promotion_on') && app('global_promotion_on') === "true") {
      $this->session_id = request()->cookie('sessionId') ?? session()->getId();
      $user = UserSessions::where('sessions', $this->session_id)->first();

      if (!$user) {
        return;
      }

      $existingPromotion = optional($user->promotions)
        ->where('promotion_type', 'counter')
        ->first();

      if ($existingPromotion && $existingPromotion->promotion_expiration_date) {
        $expirationDate = Carbon::parse($existingPromotion->promotion_expiration_date);
        $now = Carbon::now();

        $this->timer = $expirationDate->greaterThan($now)
          ? $expirationDate->diffInSeconds($now)
          : 0;
      }
    }
  }

  public function timmerexpired()
  {
    $this->timer = 0;
    $this->emit('timmerexpired');
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
