<?php

namespace App\Http\Livewire;

use App\Models\ArticleCategory;
use Livewire\Component;
use Illuminate\Support\Str;


class ShowArticlecategory extends Component
{
  public $itemId;
  public $edititem = null;
  public $delete = false;
  public $record;
  public function render()
  {
    return view('livewire.show-articlecategory', [
      'category' => $this->category
    ]);
  }
    public function mount($articlecategoryId)
  {
    $this->itemId = $articlecategoryId;
  }
  public function confirmRemoval()
  {
    $this->delete = true;
  }
  public function cancelItemRemoval()
  {
    $this->delete = false;
  }
  public function getCategoryProperty()
  {
    return ArticleCategory::find($this->itemId);
  }
  public function edit()
  {
    $this->record = [
      'name' => $this->category->name,
      'active' => $this->category->active == 1 ? true : false,
      'short_description' => $this->category->short_description,
      'start_date' => $this->category->start_date,
      'end_date' => $this->category->end_date,
      'seo_id' => $this->category->seo_id,
      'seo_title' => $this->category->seo_title,
    ];
    $this->edititem = true;
  }
  public function cancel()
  {
    $this->edititem = null;
    $this->record = [];
  }
  private function generateUniqueSeoId($name)
  {
    $seoId = Str::slug($name, '-');
    $baseSeoId = $seoId;
    $counter = 1;
    while (
      ArticleCategory::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
    ) {
      $seoId = $baseSeoId . '-' . $counter;
      $counter++;
    }
    return $seoId;
  }
  public function save()
  {
    $rec = $this->record ?? null;
    if (is_null($rec)) {
      return;
    }

    $fields = ['name', 'active', 'short_description', 'start_date', 'end_date', 'seo_id', 'seo_title'];

    foreach ($fields as $field) {
      if (array_key_exists($field, $rec)) {
        if (!empty($rec[$field])) {
          $this->category->$field = $rec[$field];
        }
      }
    }

    if (!empty($rec['seo_id'])) {
      $seo_id = $this->generateUniqueSeoId($rec['seo_id']);
    } else {
      $seo_id = $this->generateUniqueSeoId($rec['name'] ?? $this->category->name);
    }

    $this->category->seo_id = str_replace(' ', '-', $seo_id);

    $this->category->last_modified_by = auth()->user()->name;
    $this->category->save();

    $this->emit('itemSaved');
    $this->record = [];
    $this->edititem = null;

    session()->flash('notification', [
      'message' => 'Record edited successfully!',
      'type'    => 'success',
      'title'   => 'Success'
    ]);
  }
  public function deleteRecord()
  {
    $this->category->delete();


    $this->delete = false;
    return redirect()->route('articlecategory')->with('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
