<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Competitor;
use App\Models\ProductPrice;
use Livewire\WithPagination;
use App\Models\PricelistEntries;
use Illuminate\Support\Facades\Schema;
use App\Models\CompetitorProducts as ModelsCompetitorProducts;

class CompetitorProducts extends Component
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
  public $competitor;
  public $editindex;
  public $item = [];
  public $rind2 = null;
  public $rind = null;
  public $single = false;
  public $multiple = false;
  public $productsAndValues = [];
  public $row = 1;
  public $searchadd = '';


  public function render()
  {
    $relatedproducts = $this->relatedproductsQuery
      ->where(function ($query) {
        $query->whereHas('competitor', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);
    return view('livewire.competitor-products', [
      'relatedproducts' => $relatedproducts,
      'products' => $this->products
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
  public function getProductsProperty()
  {
    $relatedproductsIds = $this->relatedproducts->pluck('category_id')->toArray();
    return Product::whereNotIn('id', $relatedproductsIds)->where('name', 'like', '%' . $this->searchadd . '%')->get();
  }
  public function plus()
  {
    $this->row++;
    $this->productsAndValues[] = [
      'name' => null,
      'url' => null,
      'price' => null,
      'allow' => false,
      'itemselected' => null,
      'product' => ['name' => null, 'idrel' => null]
    ];
  }
  public function clear($index)
  {
    unset($this->productsAndValues[$index]);

    $this->productsAndValues = array_values($this->productsAndValues);

    $this->row--;
    if ($this->row < 1) {
      $this->showTable = false;
      $this->productsAndValues[] = [
        'name' => null,
        'url' => null,
        'price' => null,
        'allow' => false,
        'itemselected' => null,
        'product' => ['name' => null, 'idrel' => null]
      ];
      $this->row = 1;
    }
  }
  public function dennyselect($index)
  {
    $this->productsAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function allowselect($index)
  {
    foreach ($this->productsAndValues as &$item) {
      $item['allow'] = false;
    }
    $this->productsAndValues[$index]['allow'] = true;
    $this->searchadd = $this->productsAndValues[$index]['itemselected'];
  }
  public function selectitem($index, $id, $name)
  {
    $this->productsAndValues[$index]['itemselected'] = $name;
    $this->productsAndValues[$index]['product']['idrel'] = $id;
    $this->productsAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function saveitems()
  {
    foreach ($this->productsAndValues as  $array) {
      if (isset($array['product']['idrel'])) {
        $productprice = PricelistEntries::where('product_id', $array['product']['idrel'])
          ->first()   // or by created_at if needed
          ->value('value') ?? 0;

        ModelsCompetitorProducts::create([
          'name' => $array['name'],
          'url' => $array['url'],
          'price' => $array['price'],
          'competitor_id' => $this->competitor->id,
          'product_id' => $array['product']['idrel'],
          'internal_price	' => $productprice
        ]);
      } else {
        session()->flash('notification', [
          'message' => 'Please provide values',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
        return;
      }
    }

    $this->productsAndValues = [];
    $this->row = 1;
    $this->showTable = false;
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->mount($this->competitor, 'competitor_products');
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
      $this->checked = $this->relatedproducts->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedproductsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function getRelatedproductsProperty()
  {
    return $this->relatedproductsQuery->get();
  }

  public function getRelatedproductsQueryProperty()
  {
    return ModelsCompetitorProducts::where('competitor_id', $this->competitor->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function mount(Competitor $competitor, $tableName = null)
  {
    $this->competitor = $competitor;
    $this->columns = Schema::getColumnListing($tableName);
    $this->selectedColumns = $this->columns;
    $this->productsAndValues = [];
    $this->row = 1;
    $this->productsAndValues[] = [
        'name' => null,
        'url' => null,
        'price' => null,
        'allow' => false,
        'itemselected' => null,
        'product' => ['name' => null, 'idrel' => null]
      ];
  }

}
