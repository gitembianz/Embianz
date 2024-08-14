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
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelateitems = false;
  public $idbeingremoved = null;
  public $columns = ['Id', 'Subcategory Name', 'Category Name', 'Subcategory Description', 'Subcategory displayed elements', 'Subcategory is active?', 'Created At', 'Updated At'];

  public $selectedColumns = [];
  public $item;
  public $loadAmount = 20;

  //add declaration
  public $searchadd = '';
  public $checkedadd = [];
  public $selectPageadd = false;
  public $selectAlladd = false;
  public $idbeinglink = null;
  public $showTable = false;
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

  public function showColumnadd($column)
  {
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

  public function selectAlladd()
  {
    $this->selectAlladd = true;
    $this->checkedadd = $this->categories->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getCategoriesProperty()
  {
    $relatedcatsIds = $this->relatedsubcats->pluck('category_id')->merge([$this->item->id])->toArray();
    $unrelatedCatsQuery = Category::whereNotIn('id', $relatedcatsIds);
    if (!empty($this->searchadd)) {
      $unrelatedCatsQuery->where('name', 'like', '%' . $this->searchadd . '%');
    }
    return $unrelatedCatsQuery->paginate($this->loadAmount);
  }

  public function linkSingleRecord()
  {
    $category = Category::find($this->idbeinglink);
    $rec = new  Subcategory();
    $rec->name = $category->name;
    $rec->category_id = $category->id;
    $rec->parent_id = $this->item->id;
    $category->has_parent = true;
    $category->save();
    $rec->save();
    $this->linksingle = false;

    $this->checkedadd = array_diff($this->checkedadd, [$this->idbeinglink]);
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
      $add->parent_id = $this->item->id;
      $cat->has_parent = true;
      $cat->save();
      $add->save();
    }

    $this->checkedadd = [];
    $this->linkmultiple = false;

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


  //related subcatecory functions
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
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
    return $this->relatedsubcatsQuery;
  }
  public function getRelatedsubcatsQueryProperty()
  {
    return Subcategory::where('parent_id', $this->item->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->with('category');
  }

  public function deleteSingleRecord()
  {
    $record = Subcategory::findOrFail($this->idbeingremoved);
    $still_has_parents = Subcategory::where('category_id', $record->category_id)->count();
    if ($still_has_parents == 1) {
      $cat = Category::findOrFail($record->category_id);
      $cat->has_parent = false;
      $cat->save();
    }
    $record->delete();
    $this->single = false;

    $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
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
      $still_has_parents = Subcategory::where('category_id', $recordtodel->category_id)->count();
      if ($still_has_parents == 1) {
        $cat = Category::findOrFail($recordtodel->category_id);
        $cat->has_parent = false;
        $cat->save();
      }
      $recordtodel->delete();
    }
    $this->checked = [];
    $this->multiple = false;

    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->selectPage = false;
  }

  public function render()
  {
    $relatedsubcats = $this->relatedsubcats
      ->where(function ($query) {
        $query->whereHas('category', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('short_description', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);


    return view('livewire.related-subcategory', [
      'relatedsubcats' => $relatedsubcats,
      'categories' => $this->categories,
    ]);
  }
  public function mount(Category $category)
  {
    $this->item = $category;
    $this->selectedColumns = $this->columns;
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