<?php

namespace App\Http\Livewire;

use App\Models\Subscribers;
use Livewire\Component;

class StoreFooter extends Component
{

  public $email;
  public $response = null;

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
    session()->flash('message', 'Subscription successful!');
    $this->response = "Thank you for subscription!";
  }
}
