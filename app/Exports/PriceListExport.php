<?php

namespace App\Exports;

use App\Models\PriceList;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class PriceListExport implements FromQuery
{
  use Exportable;

  protected $prices;

  public function __construct($prices)
  {
    $this->prices = $prices;
  }

  public function query()
  {
    return PriceList::query()->whereKey($this->prices);
  }
}
