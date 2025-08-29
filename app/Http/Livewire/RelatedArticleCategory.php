<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleCategoryLink;
use Livewire\Component;
use Livewire\WithPagination;

class RelatedArticleCategory extends Component
{
  use WithPagination;
  public $showTable = false;

  //related variables
  public $loadAmount = 20;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelateitems = false;
  public $idbeingremoved = null;
  public $columns = ['Id', 'Category Name', 'Article Name', 'Article Description', 'Article is active?', 'Created At', 'Updated At'];
  public $selectedColumns = [];
  public $category;

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

public function render()
  {
    $relatedarticles = $this->relatedarticles
      ->where(function ($query) {
        $query->whereHas('article', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);

    return view('livewire.related-article-category', [
      'relatedarticles' => $relatedarticles,
      'articles' => $this->articles,
    ]);
  }
  public function mount(ArticleCategory $category)
  {
    $this->category = $category;
    $this->selectedColumns = $this->columns;
  }

  // expand row methods
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

    // function for add products
  public function addrelated()
  {
    $this->showrelateitems = true;
    $this->showTable = true;
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
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function closemodal()
  {
    $this->showTable = false;
  }
  public function showColumnadd($column)
  {
    return in_array($column, $this->selectedColumnsadd);
  }
  public function updatedSelectPageadd($value)
  {
    if ($value) {
      $this->checkedadd = $this->articles->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    return in_array($id, $this->checked);
  }
  public function updatedCheckedadd()
  {
    $this->selectPageadd = false;
  }
  public function selectAlladd()
  {
    $this->selectAlladd = true;
    $this->checkedadd = $this->articles->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getArticlesProperty()
  {
    $ids = $this->relatedarticles->pluck('article_id')->toArray();
    $unrelated = Article::whereNotIn('id', $ids);
    if (!empty($this->searchadd)) {
      $unrelated->where('name', 'like', '%' . $this->searchadd . '%');
    }

    return $unrelated->paginate($this->loadAmount);
  }

  public function linkSingleRecord()
  {
    $id = $this->idbeinglink;
    $product = new  ArticleCategoryLink();
    $product->product_id = $id;
    $product->category_id = $this->category->id;
    $product->save();
    $this->linksingle = false;

    $this->checkedadd = array_diff($this->checkedadd, [$id]);
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function linkRecords()
  {
    $products = Article::whereKey($this->checkedadd)->get();
    foreach ($products as $product) {
      $prodadd = new ArticleCategoryLink();
      $prodadd->product_id = $product->id;
      $prodadd->category_id = $this->category->id;
      $prodadd->save();
    }
    $this->selectPageadd = false;
    $this->checkedadd = [];
    $this->linkmultiple = false;

    session()->flash('notification', [
      'message' => 'Records related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }


    //function for related articles
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->relatedarticles->pluck('id')->map(fn ($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedarticles->pluck('id')->map(fn ($item) => (string) $item)->toArray();
  }
  public function getRelatedarticlesQueryProperty()
  {
    return $this->relatedarticlesQuery->get();
  }
  public function getRelatedarticlesProperty()
  {
    return ArticleCategoryLink::where('category_id', $this->category->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }
  // deleted functions
  public function confirmItemRemoval($id)
  {
    $this->idbeingremoved = $id;
    $this->single = true;
  }
  public function confirmItemsRemoval()
  {
    $this->multiple = true;
  }
   public function deleteSingleRecord()
  {
    $id = $this->idbeingremoved;
    $article = ArticleCategoryLink::findOrFail($id);
    $article->delete();
    $this->checked = array_diff($this->checked, [$id]);
    $this->single = false;

    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteRecords()
  {
    $articles = ArticleCategoryLink::whereKey($this->checked)->get();
    foreach ($articles as $article) {
      $id = $article->id;
      $articletodel = ArticleCategoryLink::find($id);
      $articletodel->delete();
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
}
