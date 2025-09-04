<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class StoreShowArticle extends Component
{
  public $articleId;
  public function render()
  {
    return view('livewire.store-show-article', [
      'article' => $this->article
    ]);
  }
  public function mount($articleId)
  {
    $this->articleId = $articleId;

  }
  public function getArticleProperty()
  {


      return Article::select('id', 'name', 'short_description', 'seo_id', 'long_description')
        ->with([
          'media' => function ($query) {
            $query->select('name', 'path', 'type')
              ->whereIn('type', ['full', 'original']);
          },

          'article_categories' => function ($query) {
            $query->select('article_id', 'category_id');
            $query->with(['category' => function ($query) {
              $query->select('id', 'name', 'short_description', 'seo_id');
            }]);
          },

        ])
        ->where('id', $this->articleId)
        ->first();
  }
}
