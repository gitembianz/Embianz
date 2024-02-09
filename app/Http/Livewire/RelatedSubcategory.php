<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use Livewire\WithPagination;

class RelatedSubcategory extends Component
{
  use WithPagination;

  //related delclaration
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelatedsub = false;
  public $categoryId;
  public $col = false;
  public $all = false;
  public $subcatidbeingremoved = null;
  public $columns = ['Id', 'Short Description', 'Created At'];
  public $selectedColumns = [];
  public $category;

  //add declaration
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
  public $showTable = false;
  public $totalRecords;
  public $loadAmount = 13;


  // function for add categories
  public function toggleTable()
  {
    $this->showrelatedsub = true;
    $this->showTable = !$this->showTable;
  }
  public function cancel()
  {
    $this->showTable = false; // Set $showTable to false to hide the table
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function showColumnadd($column)
  {
    if ($column === 'Name') {
      return true;
    }
    return in_array(
      $column,
      $this->selectedColumnsadd
    );
  }
  public function updatedSelectPageadd($value)
  {
    if ($value) {
      $this->checkedadd = $this->categories->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    return in_array(
      $id,
      $this->checked
    );
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
    $this->checkedadd = $this->categories->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getCategoriesProperty()
  {
    $relatedcatsIds = $this->relatedsubcats->pluck('category_id')->merge([$this->category->id])->toArray();
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
  public function confirmitemlink($id)
  {
    $this->catidbeinglink = $id;
    $this->dispatchBrowserEvent('show-link-modal');
  }
  public function linkSingleRecord()
  {
    $category = Category::find($this->catidbeinglink);
    $rec = new  Subcategory();
    $rec->name = $category->name;
    $rec->category_id = $category->id;
    $rec->parrent_id = $this->category->id;
    $category->has_parrent = true;
    $category->save();
    $rec->save();
    $this->checkedadd = array_diff($this->checkedadd, [$this->catidbeinglink]);
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function linkRecords()
  {
    $categories = Category::whereKey($this->checkedadd)->get();
    foreach ($categories as $category) {
      $cat = Category::find($category->id);
      $add = new Subcategory();
      $add->name = $category->name;
      $add->category_id = $category->id;
      $add->parrent_id = $this->category->id;
      $cat->has_parrent = true;
      $cat->save();
      $add->save();
    }

    $this->checkedadd = [];
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
  public function confirmLinkmultiple()
  {
    $this->dispatchBrowserEvent('show-link-modal-multiple');
  }

  //related subcatecory functions
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
      $this->checked = $this->relatedsubcats->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    return in_array(
      $id,
      $this->checked
    );
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
    $this->checked = $this->relatedsubcatsQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getRelatedsubcatsProperty()
  {
    return $this->relatedsubcatsQuery->get();
  }
  public function getRelatedsubcatsQueryProperty()
  {
    return Subcategory::where('parrent_id', $this->category->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('category');
  }
  public function confirmItemRemoval($productid)
  {
    $this->subcatidbeingremoved = $productid;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function deleteSingleRecord()
  {
    $record = Subcategory::findOrFail($this->subcatidbeingremoved);
    $still_has_parrents = Subcategory::where('category_id', $record->category_id)->count();
    if ($still_has_parrents == 1) {
      $cat = Category::findOrFail($record->category_id);
      $cat->has_parrent = false;
      $cat->save();
    }
    $record->delete();

    $this->checked = array_diff($this->checked, [$this->subcatidbeingremoved]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteRecords()
  {
    $records = Subcategory::whereKey($this->checked)->get();
    foreach ($records as $record) {
      $recordtodel = Subcategory::find($record->id);
      $still_has_parrents = Subcategory::where('category_id', $recordtodel->category_id)->count();
      if ($still_has_parrents == 1) {
        $cat = Category::findOrFail($recordtodel->category_id);
        $cat->has_parrent = false;
        $cat->save();
      }
      $recordtodel->delete();
    }
    $this->checked = [];
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->selectPage = false;
  }
  public function confirmItemsRemoval()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function render()
  {
    $relatedsubcats = $this->relatedsubcats
      ->filter(function ($subcat) {
        return strpos(strtolower($subcat->category->name), strtolower($this->search)) !== false;
      });


    if ($this->showTable === true) {
      return view('livewire.related-subcategory', [
        'relatedsubcats' => $relatedsubcats,
        'categories' => $this->categories,
      ]);
    } else {
      return view('livewire.related-subcategory', [
        'relatedsubcats' => $relatedsubcats,
      ]);
    }
  }
  public function mount(Category $category)
  {
    $this->category = $category;
    $this->selectedColumns = $this->columns;
    $this->selectedColumnsadd = $this->columnsadd;
  }
}