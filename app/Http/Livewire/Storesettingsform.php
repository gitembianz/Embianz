<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Auth;

class Storesettingsform extends Component
{
  public $parameter;
  public $value;
  public $description;


  public function render()
  {
    return view('livewire.storesettingsform');
  }

  public function store()
  {
    $this->resetErrorBag();
    $this->validate([
      'description' => 'required|string|min:20',
      'parameter' => 'required|string|min:5',
      'value' => 'required|string'
    ]);
    $values = array(
      "parameter" => $this->parameter,
      "value" => $this->value,
      "description" => $this->description,
      "createdby" => Auth::user()->name,
      "lastmodifiedby" => Auth::user()->name,
      "created_at" => now(),
      "updated_at" => now()

    );

    Store_Settings::insert($values);
    $this->reset();
    session()->flash('message', 'Record added successfully!');
  }
}
