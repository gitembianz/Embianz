<?php

namespace App\Http\Livewire;

use App\Models\Specs;
use Livewire\Component;
use App\Models\Product_Spec;
use Illuminate\Support\Facades\Auth;

class ShowSpec extends Component
{
  public $itemId;
  public $edititem = null;
  public $record;

  public function render()
  {
    return view('livewire.show-spec', [
      'spec' => $this->spec
    ]);
  }
  public function mount($itemId)
  {
    $this->itemId = $itemId;
  }
  public function confirmItemRemoval()
  {
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function getSpecProperty()
  {
    return Specs::find($this->itemId);
  }
  public function deleteSingleRecord()
  {
    $id = $this->itemId;
    $record = Specs::findOrFail($id);
    $products = Product_Spec::where('spec_id', $id)->get();
    if ($products != NULL) {
      foreach ($products as $product) {
        $product->delete();
      }
    }
    $record->delete();
    return redirect()->route('specs')->with('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function edititem()
  {
    $this->record = [
      'name' => $this->spec->name,
      'um' => $this->spec->um,
      // Add other properties as needed
    ];
    $this->edititem = true;
  }
  public function cancelitem()
  {
    $this->edititem = null;
    $this->record = [];
  }
  public function saveitem()
  {
    $rec = $this->record ?? NULL;
    if (!is_null($rec)) {
      $new = Specs::find($this->itemId);
      if (array_key_exists('name', $rec)) {
        if (!empty($rec['name'])) {
          $new->name = $rec['name'];
        } else {
          session()->flash('notification', [
            'message' => 'Please provide a value!',
            'type' => 'warning',
            'title' => 'Missing Values'
          ]);
          return;
        }
      }
      if (array_key_exists('um', $rec)) {
        if (!empty($rec['um'])) {
          $new->um = $rec['um'];
        } else {
          session()->flash('notification', [
            'message' => 'Please provide a value!',
            'type' => 'warning',
            'title' => 'Missing Values'
          ]);
          return;
        }
      }
      $new->lastmodifiedby = Auth::user()->name;
      $new->updated_at = now();
      $new->save();
      $this->emit('itemSaved');
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    }
    $this->record = [];
    $this->edititem = null;
  }
}
