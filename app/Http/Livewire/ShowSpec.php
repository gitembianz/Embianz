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
  public function getSpecQueryProperty()
  {
    return Specs::find($this->itemId);
  }
  public function getSpecProperty()
  {
    return $this->specQuery;
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
    return redirect()->route('specs')->with('message', 'Record deleted Successfully');
  }
  public function edititem()
  {
    $this->record = [
      'name' => $this->spec->name,
      'um' => $this->spec->um,
      'spec_group' => $this->spec->spec_group,
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
        $new->name = $rec['name'];
      }
      if (array_key_exists('um', $rec)) {
        $new->um = $rec['um'];
      }
      if (array_key_exists('spec_group', $rec)) {
        $new->spec_group = $rec['spec_group'];
      }
      $new->lastmodifiedby = Auth::user()->name;
      $new->updated_at = now();
      $new->save();
      $this->emit('itemSaved');
      session()->flash('message', 'Record edited successfully!');
    }
    $this->record = [];
    $this->edititem = null;
  }
}
