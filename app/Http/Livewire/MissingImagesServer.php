<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MissingImagesServer extends Component
{
  public string $title;
  public bool   $showrelated = false;

  public int $loadAmount;
  public int $allResults;

  public array $selectedColumns = ['id','full_path'];
  public array $results = [];


  public function render()
  {
    return view('livewire.missing-images-server', [
      'results' => $this->results,
    ]);
  }
  public function mount(
    string $title,
  ) {
    $this->title           = $title;

    $this->loadAmount = app()->bound('global_dashboard_limit_load')
      ? app('global_dashboard_limit_load') ?? 50
      : 50;

    $this->loadResults();
  }


  protected function loadResults()
{
    $results = [];

    $modelClass = "\\App\\Models\\Media";

    if (!class_exists($modelClass)) {
        $this->results = [];
        return;
    }

    $items = $modelClass::query()->get();

    foreach ($items as $item) {

        $relativePath = rtrim($item->path, '/\\') . '/' . ltrim($item->name, '/\\');
        $relativePath = str_replace('\\', '/', $relativePath);

        $fullPath = public_path($relativePath);

        $fullPath = str_replace('\\', '/', $fullPath);

        if (file_exists($fullPath)) {
            continue;
        }

        $results[] = [
            'id'            => $item->id,
            'full_path'     => $fullPath
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
}
