<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListSeo extends Component
{
  public string $title;
  public array  $tables = [];
  public array  $selectedColumns = ['id', 'object', 'type', 'value_length'];
  public string $column;
  public string $operator;
  public int    $caracters_count;
  public bool   $showrelated = false;
  public int    $loadAmount;
  public int $allResults;

  public array $results = [];

  public function mount(
    string $title,
    string $table,
    string $column,
    string $operator,
    $caracters_count
  ) {
    $this->title           = $title;
    $this->loadAmount = app()->bound('global_dashboard_limit_load')
      ? app('global_dashboard_limit_load') ?? 50
      : 50;
    $this->tables          = array_map('trim', explode(',', $table));

    $this->column          = $column;
    $this->operator        = $operator;
    $this->caracters_count = (int) $caracters_count;

    $this->loadResults();
  }


  protected function loadResults()
  {
    $results = [];

    foreach ($this->tables as $table) {

      if (!DB::getSchemaBuilder()->hasTable($table)) {
        continue;
      }

      $type = rtrim($table, 's');
      if ($table === 'categories') $type = 'category';

      $rows = DB::table($table)
        ->select(
          'id',
          "{$this->column} as name",
          DB::raw("LENGTH($this->column) as value_length")
        )
        ->whereRaw("LENGTH($this->column) {$this->operator} ?", [$this->caracters_count])
        ->get();

      foreach ($rows as $row) {
        $results[] = [
          'id'           => $row->id,
          'object'         => $row->name,
          'type'         => $type,
          'value_length' => $row->value_length,
        ];
      }
    }

    $this->allResults = count($results);
    $this->results = array_slice($results, 0, $this->loadAmount);
  }

  public function loadMore()
  {
    $this->loadAmount += $this->loadAmount;
    $this->loadResults();
  }

  public function render()
  {
    return view('livewire.list-seo', [
      'results' => $this->results,
    ]);
  }
}
