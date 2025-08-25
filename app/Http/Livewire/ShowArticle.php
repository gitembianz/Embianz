<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;
use Illuminate\Support\Str;


class ShowArticle extends Component
{
  public $itemId;
  public $edititem = null;
  public $delete = false;
  public $record;
  public function render()
  {
    return view('livewire.show-article', [
      'article' => $this->article
    ]);
  }
  public function mount($articleId)
  {
    $this->itemId = $articleId;
  }
  public function confirmRemoval()
  {
    $this->delete = true;
  }
  public function cancelItemRemoval()
  {
    $this->delete = false;
  }
  public function getArticleProperty()
  {
    return Article::find($this->itemId);
  }
  public function edit()
  {
    $this->record = [
      'name' => $this->article->name,
      'active' => $this->article->active == 1 ? true : false,
      'short_description' => $this->article->short_description,
      'long_description' => $this->article->long_description,
      'meta_description' => $this->article->meta_description,
      'start_date' => $this->article->start_date,
      'end_date' => $this->article->end_date,
      'seo_id' => $this->article->seo_id,
      'seo_title' => $this->article->seo_title,
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
      Article::where('seo_id', $seoId)->orWhere('seo_id', $seoId . '-' . $counter)->exists()
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

    $fields = ['name', 'active', 'short_description', 'long_description', 'meta_description', 'start_date', 'end_date', 'seo_id', 'seo_title'];

    foreach ($fields as $field) {
      if (array_key_exists($field, $rec)) {
        if (!empty($rec[$field])) {
          $this->article->$field = $rec[$field];
        }
      }
    }

    if (!empty($rec['seo_id'])) {
      $seo_id = $this->generateUniqueSeoId($rec['seo_id']);
    } else {
      $seo_id = $this->generateUniqueSeoId($rec['name'] ?? $this->article->name);
    }

    $this->article->seo_id = str_replace(' ', '-', $seo_id);

    $this->article->last_modified_by = auth()->user()->name;
    $this->article->save();

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
    $this->article->delete();


    $this->delete = false;
    return redirect()->route('articles')->with('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
}
