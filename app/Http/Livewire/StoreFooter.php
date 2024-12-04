<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Subscribers;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;

class StoreFooter extends Component
{

  public $email = null;
  public $timer = null;
  public $ischecked = false;

  public function render()
  {
    return view('livewire.store-footer');
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
