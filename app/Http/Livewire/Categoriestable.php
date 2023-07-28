<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use App\Exports\CategoriesExport;
use App\Models\Products_categories;
use Illuminate\Support\Facades\File;

class Categoriestable extends Component
{
  use WithPagination;
  public $perPage = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $catidbeingremoved = null;
  public $columns = ['Id', 'Short Description', 'Sequence', 'Created At'];
  public $selectedColumns = [];

  public function render()
  {
    return view('livewire.categoriestable', [
      'categories' => $this->categories
    ]);
  }
  public function mount()
  {
    $this->selectedColumns = $this->columns;
  }
  public function showColumn($column)
  {
    if ($column === 'Name') {
      return true;
    }
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->categories->pluck('id')->map(fn ($item) => (string) $item)->toArray();
    } else {
      $this->checked = [];
    }
  }
  public function updatedChecked()
  {
    $this->selectPage = false;
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
  public function swapSortDirection()
  {
    return $this->orderAsc === '1' ? '0' : '1';
  }
  public function selectAll()
  {
    $this->selectAll = true;
    $this->checked = $this->categoriesQuery->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getCategoriesProperty()
  {
    return $this->categoriesQuery->paginate($this->perPage);
  }
  public function getCategoriesQueryProperty()
  {
    return Category::search($this->search)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  public function deleteRecords()
  {
    $categories = Category::whereKey($this->checked)->get();
    foreach ($categories as $category) {
      $id = $category->id;
      $cattodel = Category::find($id);
      $productcat = Products_categories::where('category_id', $id)->first();
      if ($productcat != NULL) {
        $productcat->delete();
      }
      $productType = class_basename(get_class($cattodel));
      $filespath = 'media/' . $productType . '/' . $cattodel->id;
      if (File::exists($filespath)) {
        File::deleteDirectory($filespath);
      }
      $cattodel->delete();
    }
    $this->checked = [];
    session()->flash('notification', [
      'message' => 'Records deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteSingleRecord()
  {
    $id = $this->catidbeingremoved;
    $category = Category::findOrFail($id);
    $productcats = Products_categories::where('category_id', $id)->get();

    if ($productcats != NULL) {
      foreach ($productcats as $productcat) {

        $productcat->delete();
      }
    }
    $productType = class_basename(get_class($category));
    $filespath = '.media/' . $productType . '/' . $category->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }
    $category->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmCategoryRemoval($id)
  {
    $this->catidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal-category');
  }
  public function confirmCategoriesRemovalmultiple()
  {
    $this->dispatchBrowserEvent('show-delete-modal-category-multiple');
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
  public function exportSelected()
  {
    $export = new CategoriesExport($this->checked);
    $this->checked = [];
    $this->selectPage = false;
    session()->flash('notification', [
      'message' => 'Report downloaded successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    return $export->download('categories.xlsx');
  }
}
