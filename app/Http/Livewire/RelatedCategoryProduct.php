<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Products_categories;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedCategoryProduct extends Component
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
  public $columns = ['Id', 'Category Name', 'Category Description', 'Category displayed elements', 'Category is active?', 'Is primary category?', 'Created At', 'Updated At'];
  public $selectedColumns = [];
  public $product;
  public $editindex;
  public $var = [];
  public $rind2 = null;
  public $rind = null;
  public $single = false;
  public $multiple = false;
  public $productsAndValues = [];
  public $row = 1;
  public $searchadd = '';




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
  // edit item
  public function edititem($index, $id)
  {
    $this->editindex = $index;
    $record = Products_categories::find($id);
    $this->var = [
      $index . '.primary' => $record->primary_category == 1 ? true : false,
    ];
  }
  public function canceledit()
  {
    $this->editindex = null;
    $this->var = [];
  }

  public function saveitem($index, $id)
  {
    $record = $this->var[$index] ?? null;
    if (!is_null($record)) {
      $new = Products_categories::find($id);
      if (array_key_exists('primary', $record)) {
        if ($record['primary']) {
          Products_categories::where('product_id', $this->product->id)
            ->where('primary_category', true)
            ->update(['primary_category' => false]);
          $new->primary_category = $record['primary'];
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
    $this->var = [];
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
  public function getCatsProperty()
  {
    $relatedcatsIds = $this->relatedcats->pluck('category_id')->toArray();
    return Category::whereNotIn('id', $relatedcatsIds)->where('name', 'like', '%' . $this->searchadd . '%')->get();
  }
  public function plus()
  {
    $this->row++;
    $this->productsAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'product' => ['name' => null, 'primary' => false]
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
        'allow' => false,
        'itemselected' => null,
        'product' => ['name' => null, 'primary' => 0]
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
      if (isset($array['product']['primary']) && isset($array['product']['idrel'])) {
        if (isset($array['product']['primary'])) {
          Products_categories::where('product_id', $this->product->id)
            ->where('primary_category', true)
            ->update(['primary_category' => false]);
        }
        Products_categories::create([
          'product_id' => $this->product->id,
          'category_id' => $array['product']['idrel'],
          'primary_category' => $array['product']['primary']
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
    $this->mount($this->product);
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
      $this->checked = $this->relatedcats->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedcatsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function getRelatedcatsProperty()
  {
    return $this->relatedcatsQuery->get();
  }

  public function getRelatedcatsQueryProperty()
  {
    return Products_categories::where('product_id', $this->product->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function mount(Product $product)
  {
    $this->product = $product;
    $this->selectedColumns = $this->columns;
    $this->productsAndValues = [];
    $this->row = 1;
    $this->productsAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'product' => ['name' => null, 'primary' => false]
    ];
  }
  public function render()
  {
    $relatedcats = $this->relatedcatsQuery
      ->where(function ($query) {
        $query->whereHas('category', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('short_description', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);
    return view('livewire.related-category-product', [
      'relatedcats' => $relatedcats,
      'cats' => $this->cats
    ]);
  }

  // funciton for link and delete
  public function deleteSingleRecord()
  {
    $item = Products_categories::findOrFail($this->idbeingremoved);
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
    $items = Products_categories::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemtodel = Products_categories::find($id);
      $itemtodel->delete();
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
