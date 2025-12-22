<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ListRelation extends Component
{
  public string $title;
  public string $model;
  public string $relation;
  public string $operator;
  public int    $relation_count;
  public bool   $showrelated = false;

  public int $loadAmount;
  public int $allResults;

  public array $selectedColumns = ['id', 'object', 'relation', 'relations_count'];
  public array $results = [];

  public function mount(
    string $title,
    string $model,
    string $relation,
    string $operator,
    $relation_count
  ) {
    $this->title           = $title;

    $this->loadAmount = app()->bound('global_dashboard_limit_load')
      ? app('global_dashboard_limit_load') ?? 50
      : 50;

    $this->model          = $model;
    $this->relation       = $relation;
    $this->operator       = $operator;
    $this->relation_count = (int) $relation_count;

    $this->loadResults();
  }


  protected function loadResults()
  {
    $results = [];

    $modelClass = "\\App\\Models\\" . ucfirst($this->model);

    if (!class_exists($modelClass)) {
      $this->results = [];
      return;
    }

    $model = new $modelClass;

    if (!method_exists($model, $this->relation)) {
      $this->results = [];
      return;
    }
    $query = $modelClass::query()
      ->where('active', 1)
      ->whereIn('type', ['standard', 'variant']);

    if ($this->relation === 'media') {
      $query->withCount([
        'media as media_count' => function ($q) {
          $q->where('type', 'main');
        }
      ]);
    } else {
      $query->withCount($this->relation);
    }

    $items = $query
      ->having("{$this->relation}_count", $this->operator, $this->relation_count)
      ->get();


    foreach ($items as $item) {
      $results[] = [
        'id'              => $item->id,
        'object'          => $item->name,
        'relation'        => $this->relation,
        'relations_count' => $item->{$this->relation . '_count'},
      ];
    }

    $this->allResults = count($results);
    $this->results    = array_slice($results, 0, $this->loadAmount);
  }

  public function loadMore()
  {
    $this->loadAmount += $this->loadAmount;
    $this->loadResults();
  }

  public function render()
  {
    return view('livewire.list-relation', [
      'results' => $this->results,
    ]);
  }
}
