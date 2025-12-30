<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\ArticleCategory;
use Livewire\Component;

class StoreBlog extends Component
{
  public $category;
  public $loadAmount;
  public $search = '';
  public $orderBy = 'date_new_old';


  public function render()
  {
    return view('livewire.store-blog', [
      'articles' => $this->articles
    ]);
  }
  public function mount($category = null)
  {
    if ($category) {
      $decodedCategory = json_decode(htmlspecialchars_decode($category), true);
      $this->category = ArticleCategory::select('id', 'name', 'short_description', 'seo_id')->find($decodedCategory['id']);
    } else {
      $this->category = null;
    }
    $filteredValues = session()->get('filtered_values', []);

    if (isset($filteredValues['loadAmount'])) {
      $this->loadAmount = $filteredValues['loadAmount'];
    } else {
      session()->forget('filtered_values');
      $this->loadAmount = app()->bound('global_articles_limit_load')
    ? app('global_articles_limit_load')
    : 10;

    }
  }
  public function loadMore()
  {
    $this->loadAmount += app()->bound('global_articles_limit_load')
    ? app('global_articles_limit_load')
    : 10;
    if ($this->category != null) {
      session()->put('filtered_values', [
        'category_id' => $this->category->id,
        'loadAmount' =>  $this->loadAmount
      ]);
    } else {
      session()->put('filtered_values', [
        'loadAmount' =>  $this->loadAmount
      ]);
    }
  }
  public function getArticlesProperty()
  {
    $query = Article::search($this->search)
      ->where('active', true)
      ->where('start_date', '<=', now(config('app.timezone'))->format('Y-m-d'))
      ->where('end_date', '>=', now(config('app.timezone'))->format('Y-m-d'))
      ->with([
        'article_categories' => function ($query) {
          $query->select('article_id', 'category_id', 'primary_category')
            ->where('primary_category', true);
          $query->with(['category' => function ($query) {
            $query->select('id', 'short_description', 'seo_id');
          }]);
        },
        'media' => function ($query) {
          $query->select('name', 'path', 'type')
            ->where('type', 'main');
        }
      ]);

    if ($this->category != null) {
      $query->whereHas('article_categories.category', function ($query) {
        $query->where('id', $this->category->id);
      });
    }
    switch ($this->orderBy) {
      case 'name_az':
        $query->orderBy('name');
        break;
      case 'name_za':
        $query->orderBy('name', 'desc');
        break;
      case 'date_old_new':
        $query->orderBy('created_at');
        break;
      case 'date_new_old':
        $query->orderBy('created_at', 'desc');
        break;
    }
    if(app()->has('global_blog_pagination') && app('global_blog_pagination') === 'links'){
      return $query->orderBy('created_at', 'DESC')->get();

    }else{
      return $query->orderBy('created_at', 'DESC')->paginate($this->loadAmount);
    }
  }
}
