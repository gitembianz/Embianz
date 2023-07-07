<?php

namespace App\Exports;

use App\Models\Store_Settings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;

class StoreSettingsExport implements FromQuery
{
  use Exportable;

  protected $store;

  public function __construct($store)
  {
    $this->store = $store;
  }

  public function query()
  {
    return Store_Settings::query()->whereKey($this->store);
  }
}
