<?php

namespace App\Http\Livewire;

use App\Models\Product_Spec;
use App\Models\Specs;
use Livewire\Component;

class ShowSpec extends Component
{

  public $itemId;
  public $edititem = null;

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
}
