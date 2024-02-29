<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Store_Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


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
      'description' => 'required',
      'parameter' => 'required',
      'value' => 'required'
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
    Cache::forget('global_variables');
    session()->flash('notification', [
      'message' => 'Record added successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
