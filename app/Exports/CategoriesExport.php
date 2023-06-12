<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class CategoriesExport implements FromQuery
{
  use Exportable;

  protected $categories;

  public function __construct($categories)
  {
      $this->categories = $categories;
  }

  public function query()
  {
      return Category::query()->whereKey($this->categories);
  }
}
