<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use Livewire\WithPagination;
use App\Models\Products_categories;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class Categoriestable extends Component
{
  use WithPagination;
  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $catidbeingremoved = null;
  public $selectedColumns = [];
  public $col = false;
  public $all = false;
  public $tableName;
  public $columns;

  public function render()
  {
    if ($this->all) {
      $this->selectedColumns = $this->columns;
    }

    return view('livewire.categoriestable', ['categories' => $this->categories]);
  }
  public function mount($tableName)
  {
    $this->tableName = $tableName;
    $this->columns = Schema::getColumnListing($this->tableName);

    // Exclude 'long_description' and 'short_description' columns
    $excludedColumns = ['long_description', 'short_description'];
    $this->selectedColumns = array_diff($this->columns, $excludedColumns);
    $this->columns = array_diff($this->columns, $excludedColumns);
  }

  public function updatedSelectedColumns()
  {
    session(['selectedColumns' => $this->selectedColumns]);
  }
  public function showColumn($column)
  {
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
    return $this->categoriesQuery->limit($this->loadAmount)->get();
  }
  public function loadMore()
  {
    $this->loadAmount += 10;
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
      $productcat = Products_categories::where('category_id', $id)->get();
      if ($productcat != NULL) {
        foreach ($productcat as $pro) {
          $pro->delete();
        }
      }
      $subcategories = Subcategory::where('parrent_id', $id)->get();
      if ($subcategories != NULL) {
        foreach ($subcategories as $sub) {
          $sub->delete();
        }
      }
      $medias = $cattodel->media()->get();
      foreach ($medias as $media) {
        $media->delete();
      }
      $productType = class_basename(get_class($cattodel));
      $filespath = 'media/' . $productType . '/' . $cattodel->id;
      if (File::exists($filespath)) {
        File::deleteDirectory($filespath);
      }
      $cattodel->delete();
    }
    $this->checked = [];
    $this->selectPage = false;
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
    $subcategories = Subcategory::where('parrent_id', $id)->get();
    if ($subcategories != NULL) {
      foreach ($subcategories as $sub) {
        $sub->delete();
      }
    }
    $productType = class_basename(get_class($category));
    $filespath = 'media/' . $productType . '/' . $category->id;
    if (File::exists($filespath)) {
      File::deleteDirectory($filespath);
    }
    $medias = $category->media()->get();
    foreach ($medias as $media) {
      $media->delete();
    }
    $category->delete();
    $this->checked = array_diff($this->checked, [$id]);
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function confirmItemRemoval($id)
  {
    $this->catidbeingremoved = $id;
    $this->dispatchBrowserEvent('show-delete-modal');
  }
  public function confirmItemsRemoval()
  {
    $this->dispatchBrowserEvent('show-delete-modal-multiple');
  }
  public function isChecked($id)
  {
    return in_array($id, $this->checked);
  }
}
