<?php

namespace App\Http\Livewire;

use App\Models\Currency;
use Livewire\Component;
use App\Models\PriceList;
use App\Models\PricelistEntries;
use Illuminate\Support\Facades\Auth;

class ShowPricelist extends Component
{

  public $itemId;
  public $edititem = null;
  public $record;
  public $currencies;

  public function render()
  {
    return view('livewire.show-pricelist', [
      'pricelist' => $this->pricelist
    ]);
  }

  public function mount($itemId)
  {
    $this->itemId = $itemId;
    $this->currencies = Currency::all();
  }
  public function confirmItemRemoval()
  {
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function getPricelistQueryProperty()
  {
    return PriceList::find($this->itemId);
  }
  public function getPricelistProperty()
  {
    return $this->pricelistQuery;
  }
  public function deleteSingleRecord()
  {
    $id = $this->itemId;
    $record = PriceList::findOrFail($id);
    $products = PricelistEntries::where('pricelist_id', $id)->get();
    if ($products != NULL) {
      foreach ($products as $product) {
        $product->delete();
      }
    }
    $record->delete();
    return redirect()->route('pricelists')->with('message', 'Record deleted Successfully');
  }
  public function edititem()
  {
    $this->record = [
      'name' => $this->pricelist->name,
      'currency' => $this->pricelist->currency->id,
      'active' => $this->pricelist->active,
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
      $new = PriceList::find($this->itemId);
      if (array_key_exists('name', $rec)) {
        $new->name = $rec['name'];
      }
      if (array_key_exists('currency', $rec)) {
        $new->currency_id = $rec['currency'];
      }
      if (array_key_exists('active', $rec)) {
        $new->active = $rec['active'];
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
