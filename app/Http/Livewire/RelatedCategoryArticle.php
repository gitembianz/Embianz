<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleCategoryLink;
use Livewire\Component;
use Livewire\WithPagination;


class RelatedCategoryArticle extends Component
{
  use WithPagination;
  public $showTable = false;

  //related variables
  public $loadAmount = 10;
  public $search = '';
  public $orderBy = 'id';
  public $orderAsc = true;
  public $checked = [];
  public $selectPage = false;
  public $selectAll = false;
  public $showrelateitems = false;
  public $idbeingremoved = null;
  public $columns = ['Id', 'Category Name', 'Category is active?', 'Is primary category?', 'Created At', 'Updated At'];
  public $selectedColumns = [];
  public $article;
  public $editindex;
  public $var = [];
  public $rind2 = null;
  public $rind = null;
  public $single = false;
  public $multiple = false;
  public $articlesAndValues = [];
  public $row = 1;
  public $searchadd = '';


   public function render()
  {
    $relatedcats = $this->relatedcatsQuery
      ->where(function ($query) {
        $query->whereHas('category', function ($subQuery) {
          $subQuery->where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('short_description', 'LIKE', '%' . $this->search . '%');
        });
      })->paginate($this->loadAmount);
    return view('livewire.related-category-article', [
      'relatedcats' => $relatedcats,
      'cats' => $this->cats
    ]);
  }
   public function mount(Article $article)
  {
    $this->article = $article;
    $this->selectedColumns = $this->columns;
    $this->articlesAndValues = [];
    $this->row = 1;
    $this->articlesAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'article' => ['name' => null, 'primary' => false]
    ];
  }

  // expand
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

    // edit item
  public function edititem($index, $id)
  {
    $this->editindex = $index;
    $record = ArticleCategoryLink::find($id);
    $this->var = [
      $index . '.primary' => $record->primary_category == 1 ? true : false,
    ];
  }
  public function canceledit()
  {
    $this->editindex = null;
    $this->var = [];
  }
  public function saveitem($index, $id)
  {
    $record = $this->var[$index] ?? null;
    if (!is_null($record)) {
      $new = ArticleCategoryLink::find($id);
      if (array_key_exists('primary', $record)) {
        if ($record['primary']) {
          ArticleCategoryLink::where('article_id', $this->article->id)
            ->where('primary_category', true)
            ->update(['primary_category' => false]);
          $new->primary_category = $record['primary'];
        }
      }
      $new->save();
      session()->flash('notification', [
        'message' => 'Record edited successfully!',
        'type' => 'success',
        'title' => 'Success'
      ]);
    } else {
      session()->flash('notification', [
        'message' => 'Nothing was edited!',
        'type' => 'warning',
        'title' => 'Warning'
      ]);
    }
    $this->editindex = null;
    $this->var = [];
  }

   // add related
  public function addrelated()
  {
    $this->showrelateitems = true;
    $this->showTable = true;
  }
  public function closemodal()
  {
    $this->showTable = false;
  }
  public function getCatsProperty()
  {
    $relatedcatsIds = $this->relatedcats->pluck('category_id')->toArray();
    return ArticleCategory::whereNotIn('id', $relatedcatsIds)->where('name', 'like', '%' . $this->searchadd . '%')->get();
  }
  public function plus()
  {
    $this->row++;
    $this->articlesAndValues[] = [
      'allow' => false,
      'itemselected' => null,
      'article' => ['name' => null, 'primary' => false]
    ];
  }
  public function clear($index)
  {
    unset($this->articlesAndValues[$index]);

    $this->articlesAndValues = array_values($this->articlesAndValues);

    $this->row--;
    if ($this->row < 1) {
      $this->showTable = false;
      $this->articlesAndValues[] = [
        'allow' => false,
        'itemselected' => null,
        'article' => ['name' => null, 'primary' => 0]
      ];
      $this->row = 1;
    }
  }
  public function dennyselect($index)
  {
    $this->articlesAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function allowselect($index)
  {
    foreach ($this->articlesAndValues as &$item) {
      $item['allow'] = false;
    }
    $this->articlesAndValues[$index]['allow'] = true;
    $this->searchadd = $this->articlesAndValues[$index]['itemselected'];
  }
  public function selectitem($index, $id, $name)
  {
    $this->articlesAndValues[$index]['itemselected'] = $name;
    $this->articlesAndValues[$index]['article']['idrel'] = $id;
    $this->articlesAndValues[$index]['allow'] = false;
    $this->searchadd = '';
  }
  public function saveitems()
  {
    foreach ($this->articlesAndValues as  $array) {
      if (isset($array['article']['primary']) && isset($array['article']['idrel'])) {
        if (isset($array['article']['primary'])) {
          ArticleCategoryLink::where('article_id', $this->article->id)
            ->where('primary_category', true)
            ->update(['primary_category' => false]);
        }
        ArticleCategoryLink::create([
          'article_id' => $this->article->id,
          'category_id' => $array['article']['idrel'],
          'primary_category' => $array['article']['primary']
        ]);
      } else {
        session()->flash('notification', [
          'message' => 'Please provide values',
          'type' => 'warning',
          'title' => 'Missing Values'
        ]);
        return;
      }
    }

    $this->articlesAndValues = [];
    $this->row = 1;
    $this->showTable = false;
    session()->flash('notification', [
      'message' => 'Record related successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
    $this->mount($this->article);
  }

    //Related item function
  public function loadMore()
  {
    $this->loadAmount += 10;
  }
  public function showColumn($column)
  {
    return in_array($column, $this->selectedColumns);
  }
  public function updatedSelectPage($value)
  {
    if ($value) {
      $this->checked = $this->relatedcats->pluck('id')->map(fn($item) => (string) $item)->toArray();
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
    $this->checked = $this->relatedcatsQuery->pluck('id')->map(fn($item) => (string) $item)->toArray();
  }
  public function getRelatedcatsProperty()
  {
    return $this->relatedcatsQuery->get();
  }

  public function getRelatedcatsQueryProperty()
  {
    return ArticleCategoryLink::where('article_id', $this->article->id)
      ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');
  }


    // funciton for link and delete
  public function deleteSingleRecord()
  {
    $item = ArticleCategoryLink::findOrFail($this->idbeingremoved);
    $item->delete();
    $this->checked = array_diff($this->checked, [$this->idbeingremoved]);
    $this->single = false;
    session()->flash('notification', [
      'message' => 'Record deleted successfully!',
      'type' => 'success',
      'title' => 'Success'
    ]);
  }
  public function deleteRecords()
  {
    $items = ArticleCategoryLink::whereKey($this->checked)->get();
    foreach ($items as $item) {
      $id = $item->id;
      $itemtodel = ArticleCategoryLink::find($id);
      $itemtodel->delete();
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
}
