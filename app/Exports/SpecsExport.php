<?php

namespace App\Exports;

use App\Models\Specs;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class SpecsExport implements FromQuery
{
  use Exportable;

  protected $specs;

  public function __construct($specs)
  {
      $this->specs = $specs;
  }

  public function query()
  {
      return Specs::query()->whereKey($this->specs);
  }
}
