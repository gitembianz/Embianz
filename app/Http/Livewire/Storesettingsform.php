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

  public $totalSteps = 3;
  public $currentStep = 1;

  public function mount()
  {
    $this->currentStep = 1;
  }

  public function render()
  {
    return view('livewire.storesettingsform');
  }

  public function increaseStep()
  {
    $this->resetErrorBag();
    $this->validateData();
    $this->currentStep++;
    if ($this->currentStep >= $this->totalSteps) {
      $this->currentStep = $this->totalSteps;
    }
  }

  public function decreaseStep()
  {
    $this->resetErrorBag();
    $this->currentStep--;
    if ($this->currentStep < 1) {
      $this->currentStep = 1;
    }
  }

  public function validateData()
  {
    if ($this->currentStep == 1) {
      $this->validate([
        'parameter' => 'required|string|min:5'
      ]);
    } elseif ($this->currentStep == 2) {
      $this->validate([
        'value' => 'required|string'
      ]);
    }
  }

  public function store()
  {
    $this->resetErrorBag();
    if ($this->currentStep == 3) {
      $this->validate([
        'description' => 'required|string|min:20'
      ]);
    }
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
    $this->currentStep == 1;
    session()->flash('message', 'Record added successfully!');
  }
}
