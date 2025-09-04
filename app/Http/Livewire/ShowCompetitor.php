<?php

namespace App\Http\Livewire;

use App\Models\Competitor;
use Livewire\Component;

class ShowCompetitor extends Component
{
  public $itemId;
  public $edititem = null;
  public $delete = false;
  public $record;
  public function render()
  {
    return view('livewire.show-competitor', [
      'competitor' => $this->competitor
    ]);
  }
     public function mount($competitorId)
  {
    $this->itemId = $competitorId;
  }
  public function confirmRemoval()
  {
    $this->delete = true;
  }
  public function cancelItemRemoval()
  {
    $this->delete = false;
  }
  public function getCompetitorProperty()
  {
    return Competitor::find($this->itemId);
  }
   public function edit()
  {
    $this->record = [
      'name' => $this->competitor->name,
      'url' => $this->competitor->url
    ];
    $this->edititem = true;
  }
  public function cancel()
  {
    $this->edititem = null;
    $this->record = [];
  }
  public function save()
  {
    $rec = $this->record ?? null;
    if (is_null($rec)) {
      return;
    }

    $fields = ['name', 'url'];

    foreach ($fields as $field) {
      if (array_key_exists($field, $rec)) {
        if (!empty($rec[$field])) {
          $this->competitor->$field = $rec[$field];
        }
      }
    }
    $this->competitor->last_modified_by = auth()->user()->name;
    $this->competitor->save();
    $this->emit('itemSaved');
    $this->record = [];
    $this->edititem = null;

    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type'    => 'success',
      'title'   => 'Success'
    ]);
  }
  public function deleteRecord()
  {
    $this->competitor->delete();
    $this->delete = false;
    return redirect()->route('competitors')->with('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
