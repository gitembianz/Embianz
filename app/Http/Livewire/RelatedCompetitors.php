<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Competitor;
use Livewire\WithPagination;
use App\Models\PricelistEntries;
use Illuminate\Support\Facades\Schema;
use App\Models\CompetitorProducts as ModelsCompetitorProducts;

class RelatedCompetitors extends Component
{

  use WithPagination;
  public $showTable = false;

  //related variables
  public $loadAmount = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelateitems = false;
  public $idbeingremoved = null;
  public $columns;
  public $selectedColumns = [];
  public $product;
  public $editindex;
  public $item = [];
  public $rind2 = null;
  public $rind = null;
  public $single = false;
  public $multiple = false;
  public $competitorsAndValues = [];
  public $row = 1;
  public $searchadd = '';

  public function render()
  {
    $relatedcompetitors = $this->relatedcompetitorsQuery
      ->where(function ($query) {
        $query->whereHas('competitor', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);
    return view('livewire.related-competitors', [
      'relatedcompetitors' => $relatedcompetitors,
      'competitors' => $this->competitors
    ]);
  }
    // expand
  public function expandRow2($index)
  {
    if ($this->rind2  === null) {
      $this->rind2 = $index;
    } elseif ($this->rind2 != $index) {
      $this->rind2 = $index;
    } else {
      $this->rind2 = null;
    }
  }
  public function expandRow($index)
  {
    if ($this->rind  === null) {
      $this->rind = $index;
    } elseif ($this->rind != $index) {
      $this->rind = $index;
    } else {
      $this->rind = null;
    }
  }
    public function edititem($index, $id)
  {
    $this->editindex = $index;
    $record = ModelsCompetitorProducts::find($id);
    $this->item[$index] = [
      'name' => $record->name,
      'url' => $record->url,
      'price' => $record->price,
    ];
  }
  public function canceledit()
  {
    $this->editindex = null;
    $this->item = [];
  }
    public function saveitem($index, $id)
  {
    $record = $this->item[$index] ?? null;
    if (!is_null($record)) {
      $new = ModelsCompetitorProducts::find($id);
      if (array_key_exists('name', $record)) {
        $new->name = $record['name'];
      }
      if (array_key_exists('url', $record)) {
        $new->url = $record['url'];
      }
      if (array_key_exists('price', $record)) {
        $new->price = $record['price'];
        if($new->internal_price !=0){

          $differenceValue = $record['price'] - $new->internal_price;

          $differencePercentage = $new->internal_price > 0
            ? round(($differenceValue / $new->internal_price) * 100, 2)
            : 0;
          $new->difference_value = $differenceValue;
          $new->difference_percent = $differencePercentage;
        }else{
          $new->difference_value = 0;
          $new->difference_percent = 0;
        }

      }
      $new->save();
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      session()->flash('notification', [
        'message' => 'Nothing was edited!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
    }
    $this->editindex = null;
    $this->item = [];
  }
  // add related
  public function addrelated()
  {
    $this->showrelateitems = true;
    $this->showTable = true;
  }
  public function closemodal()
  {
    $this->showTable = false;
  }
  public function getCompetitorsProperty()
  {
    $relatedcompetitorsIds = $this->relatedcompetitors->pluck('competitor_id')->toArray();
    return Competitor::whereNotIn('id', $relatedcompetitorsIds)->where('name', 'like', '%' . $this->searchadd . '%')->get();
  }
  public function plus()
  {
    $this->row++;
    $this->competitorsAndValues[] = [
      'name' => null,
      'url' => null,
      'price' => null,
      'allow' => false,
      'itemselected' => null,
      'competitor' => ['name' => null, 'idrel' => null]
    ];
  }
    public function clear($index)
  {
    unset($this->productsAndValues[$index]);

    $this->competitorsAndValues = array_values($this->competitorsAndValues);

    $this->row--;
    if ($this->row < 1) {
      $this->showTable = false;
      $this->competitorsAndValues[] = [
        'name' => null,
        'url' => null,
        'price' => null,
        'allow' => false,
        'itemselected' => null,
        'competitor' => ['name' => null, 'idrel' => null]
      ];
      $this->row = 1;
    }
  }
    public function dennyselect($index)
  {
    $this->competitorsAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function allowselect($index)
  {
    foreach ($this->competitorsAndValues as &$item) {
      $item['allow'] = false;
    }
    $this->competitorsAndValues[$index]['allow'] = true;
    $this->searchadd = $this->competitorsAndValues[$index]['itemselected'];
  }
   public function selectitem($index, $id, $name)
  {
    $this->competitorsAndValues[$index]['itemselected'] = $name;
    $this->competitorsAndValues[$index]['competitor']['idrel'] = $id;
    $this->competitorsAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function saveitems()
  {
    $productprice = PricelistEntries::where('product_id', $this->product->id)
      ->latest('created_at')
      ->value('value') ?? 0;
    foreach ($this->competitorsAndValues as $array) {
      if (isset($array['product']['idrel'])) {

        $competitorPrice = $array['price'] ?? 0;

        $differenceValue = $competitorPrice - $productprice;

        $differencePercentage = $productprice > 0
          ? round(($differenceValue / $productprice) * 100, 2)
          : 0;
      } else {
        $productprice = 0;
        $competitorPrice = $array['price'] ?? 0;
        $differenceValue = 0;
        $differencePercentage = 0;
      }
      ModelsCompetitorProducts::create([
        'name' => $array['name'],
        'url' => $array['url'],
        'price' => $competitorPrice,
        'competitor_id' => $array['competitor']['idrel'],
        'product_id' => $this->product->id,
        'internal_price' => $productprice,
        'difference_value' => $differenceValue,
        'difference_percent' => $differencePercentage,
        'created_by' => auth()->user()->name,
        'last_modified_by' => auth()->user()->name,
      ]);
    }


    $this->competitorsAndValues = [];
    $this->row = 1;
    $this->showTable = false;
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->mount($this->product, 'competitor_products');
  }
   //Related item function
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->relatedcompetitors->pluck('id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
   public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
  }
    public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function sortBy($columnName)
  {

    if ($this->orderBy === $columnName) {
      $this->orderAsc = $this->swapSortDirection();
    } else {
      $this->orderAsc = '1';
    }

    $this->orderBy = $columnName;
  }
    public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->relatedcompetitorsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function getRelatedcompetitorsProperty()
  {
    return $this->relatedcompetitorsQuery->get();
  }
    public function getRelatedcompetitorsQueryProperty()
  {
    return ModelsCompetitorProducts::where('product_id', $this->product->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function mount(Product $product, $tableName = null)
  {
    $this->product = $product;
    $this->columns = Schema::getColumnListing($tableName);
    $this->selectedColumns = $this->columns;
    $this->competitorsAndValues = [];
    $this->row = 1;
    $this->competitorsAndValues[] = [
      'name' => null,
      'url' => null,
      'price' => null,
      'allow' => false,
      'itemselected' => null,
      'competitor' => ['name' => null, 'idrel' => null]
    ];
  }
  // funciton for link and delete
  public function deleteSingleRecord()
  {
    $item = ModelsCompetitorProducts::findOrFail($this->idbeingremoved);
    $item->delete();
    $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
    $this->single = false;
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteRecords()
  {
    $items = ModelsCompetitorProducts::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $item->delete();
    }
    $this->checked = [];
    $this->selectPage = false;
    $this->multiple = false;
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemRemoval($id)
  {
    $this->idbeingremoved = $id;
    $this->single = true;
  }
  public function confirmItemsRemoval()
  {
    $this->multiple = true;
  }
  public function cancel_delete()
  {
    $this->multiple = false;
    $this->single = false;
  }
}
