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


  //add variables
  public $searchadd = '';
  public $checkedadd = [];
  public $selectPageadd = false;
  public $selectAlladd = false;
  public $idbeinglink = null;

  public $linksingle = false;
  public $linkmultiple = false;
  public $single = false;
  public $multiple = false;
  public $rind2 = null;
  public $rind = null;


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

  public function addrelated()
  {
    $this->showrelateitems = true;
    $this->showTable = true;
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function closemodal()
  {
    $this->showTable = false;
  }

  public function updatedSelectPageadd($value)
  {
    if ($value) {
      $this->checkedadd = $this->cats->pluck('id')->map(fn($item) => (string) $item)->toArray();
    } else {
      $this->checkedadd = [];
    }
  }

  public function isCheckedadd($id)
  {
    return in_array($id, $this->checkedadd);
  }

  public function selectAlladd()
  {
    $this->selectAlladd = true;
    $this->checkedadd = $this->cats->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function getCatsProperty()
  {
    $relatedcatsIds = $this->relatedcats->pluck('category_id')->toArray();
    $unrelatedCatsQuery = Category::whereNotIn('id', $relatedcatsIds);
    if (!empty($this->searchadd)) {
      $unrelatedCatsQuery->where('name', 'like', '%' . $this->searchadd . '%');
    }
    if ($this->selectAlladd) {
      return $unrelatedCatsQuery->get();
    } else {
      return $unrelatedCatsQuery->paginate($this->loadAmount);
    }
  }

  public function updatedCheckedadd()
  {
    $this->selectPageadd = false;
  }

  //Related item function
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function load()
  {
    $this->loadAmount += 10;
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

  public function cancel_link()
  {
    $this->linkmultiple = false;
    $this->linksingle = false;
  }
  public function linkSingleRecord()
  {
    $id = $this->idbeinglink;
    $item = new  Products_categories();
    $item->product_id = $this->product->id;
    $item->category_id = $id;
    $item->save();
    $this->checkedadd = array_diff($this->checkedadd, [$id]);
    $this->linksingle = false;
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function linkRecords()
  {
    $items = Category::whereKey($this->checkedadd)->get();
    foreach ($items as $item) {
      $itemadd = new Products_categories();
      $itemadd->product_id = $this->product->id;
      $itemadd->category_id = $item->id;
      $itemadd->save();
    }
    $this->checkedadd = [];
    $this->selectPageadd = false;
    $this->linkmultiple = false;
    session()->flash('notification', [
      'message' => 'Records related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemLink($id)
  {
    $this->idbeinglink = $id;
    $this->linksingle = true;
  }
  public function confirmItemsLink()
  {
    $this->linkmultiple = true;
  }
}