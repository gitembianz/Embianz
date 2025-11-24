<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MissingImagesDatabase extends Component
{
  public string $title;
  public bool   $showrelated = false;

  public int $loadAmount;
  public int $allResults;

  public array $selectedColumns = ['folder', 'model_id','file','relative','full_path'];
  public array $results = [];


  public function render()
  {
    return view('livewire.missing-images-database', [
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

    $folders = ['Article', 'Brand', 'Category', 'Product'];

    $dbPaths = \App\Models\Media::pluck('path', 'id')->toArray();
    $dbNames = \App\Models\Media::pluck('name', 'id')->toArray();

    // Build list like:  media/Product/1607/filename.jpg
    $dbFullRelative = [];
    foreach ($dbPaths as $id => $path) {
      $dbFullRelative[] = str_replace('\\', '/', rtrim($path, '/\\') . '/' . $dbNames[$id]);
    }

    $mediaRoot = public_path('media');

    foreach ($folders as $folder) {

      $modelFolder = $mediaRoot . DIRECTORY_SEPARATOR . $folder;

      if (!is_dir($modelFolder)) {
        continue; // skip missing folders
      }

      $ids = scandir($modelFolder);
      foreach ($ids as $idFolder) {

        if ($idFolder === '.' || $idFolder === '..') {
          continue;
        }

        $fullModelIdPath = $modelFolder . DIRECTORY_SEPARATOR . $idFolder;

        if (!is_dir($fullModelIdPath)) {
          continue;
        }

        $files = scandir($fullModelIdPath);

        foreach ($files as $file) {

          if ($file === '.' || $file === '..') {
            continue;
          }

          $relativePath = "media/$folder/$idFolder/$file";
          $fullPath     = str_replace('\\', '/', public_path($relativePath));

          if (!in_array($relativePath, $dbFullRelative)) {
            $results[] = [
              'folder'      => $folder,
              'model_id'    => $idFolder,
              'file'        => $file,
              'relative'    => $relativePath,
              'full_path'   => $fullPath
            ];
          }
        }
      }
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
