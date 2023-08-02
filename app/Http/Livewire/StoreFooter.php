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

  public function render()
  {
    return view('livewire.store-footer', [
      'categories' => $this->categories
    ]);
  }

  public function store()
  {
    $this->resetErrorBag();

    $validatedData = $this->validate([
      'email' => 'required|email'
    ]);

    Subscribers::create($validatedData);

    $this->reset();
    $this->response = "Thank you for subscription!";
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
