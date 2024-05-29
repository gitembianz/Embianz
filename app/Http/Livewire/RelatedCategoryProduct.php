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
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelatedcat = false;
  public $col = false;
  public $all = false;
  public $catidbeingremoved = null;
  public $columns = ['Id', 'Short Description', 'Created At'];
  public $selectedColumns = [];
  public $product;

  //add variables
  public $searchadd = '';
  public $orderByadd = 'id';
  public $orderAscadd = true;
  public $checkedadd = [];
  public $selectPageadd = false;
  public $selectAlladd = false;
  public $coladd = false;
  public $alladd = false;
  public $columnsadd = ['Id', 'Short Description', 'Created At'];
  public $selectedColumnsadd = [];
  public $catidbeinglink = null;
  public $totalRecords;
  public $loadAmount = 10;

  public function toggleTable()
  {
    $this->showrelatedcat = true;
    $this->showTable = !$this->showTable;
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function cancel()
  {
    $this->showTable = false;
  }
  public function showColumnadd($column)
  {
    if ($column === 'Name') {
      return true;
    }
    return in_array($column, $this->selectedColumnsadd);
  }
  public function updatedSelectPageadd($value)
  {
    if ($value) {
      $this->checkedadd = $this->cats->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checkedadd = [];
    }
  }
  public function swapSortDirectionadd()
  {
    return $this->orderAscadd === '1' ? '0' : '1';
  }
  public function isCheckedadd($id)
  {
    return in_array($id, $this->checkedadd);
  }
  public function sortByadd($columnName)
  {

    if ($this->orderByadd === $columnName) {
      $this->orderAscadd = $this->swapSortDirectionadd();
    } else {
      $this->orderAscadd = '1';
    }

    $this->orderByadd = $columnName;
  }
  public function selectAlladd()
  {
    $this->selectAlladd = true;
    $this->checkedadd = $this->cats->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getCatsProperty()
  {
    $relatedcatsIds = $this->relatedcats->pluck('category_id')->toArray();
    $unrelatedCatsQuery = Category::whereNotIn('id', $relatedcatsIds);
    if (!empty($this->searchadd)) {
      $unrelatedCatsQuery->where('name', 'like', '%' . $this->searchadd . '%');
    }

    $unrelatedCatsQuery->orderBy($this->orderByadd, $this->orderAscadd ? 'asc' : 'desc');
    if ($this->selectAlladd) {
      return $unrelatedCatsQuery->get();
    } else {
      return $unrelatedCatsQuery->limit($this->loadAmount)->get();
    }
  }
  public function confirmItemlink($itemtid)
  {
    $this->catidbeinglink = $itemtid;
    $this->dispatchBrowserEvent('show-link-modal');
  }
  public function linkSingleRecord()
  {
    $id = $this->catidbeinglink;
    $item = new  Products_categories();
    $item->product_id = $this->product->id;
    $item->category_id = $id;
    $item->save();
    $this->checkedadd = array_diff($this->checkedadd, [$id]);
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemsLinkmultiple()
  {
    $this->dispatchBrowserEvent('show-link-modal-multiple');
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
    session()->flash('notification', [
      'message' => 'Records related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function updatedCheckedadd()
  {
    $this->selectPageadd = false;
  }

  //Related item function
  public function showColumn($column)
  {
    if ($column === 'Name') {
      return true;
    }
    return in_array($column, $this->selectedColumns);
  }
  public function load()
  {
    $this->perPage += 10;
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->relatedcats->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedcatsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
  public function ItemRemoval($id)
  {
    $this->catidbeingremoved = $id;
    $this->dispatchBrowserEvent('delete-modal-category');
  }
  public function deleteSingleRecord()
  {
    $item = Products_categories::findOrFail($this->catidbeingremoved);
    $item->delete();
    $this->checked = array_diff($this->checked, [$this->catidbeingremoved]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemsRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
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
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function mount(Product $product)
  {
    $this->product = $product;
    $this->selectedColumns = $this->columns;
    $this->selectedColumnsadd = $this->columnsadd;
  }
  public function render()
  {
    $relatedcats = $this->relatedcatsQuery
      ->where(function ($query) {
        $query->whereHas('category', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('short_description', 'LIKE', '%' . $this->search . '%');
        });
      })->get();
    if ($this->showTable === true) {
      return view('livewire.related-category-product', [
        'relatedcats' => $relatedcats,
        'cats' => $this->cats
      ]);
    } else {
      return view('livewire.related-category-product', [
        'relatedcats' => $this->relatedcats
      ]);
    }
  }
}
