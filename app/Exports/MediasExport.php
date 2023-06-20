<?php

namespace App\Exports;

use App\Models\Media;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class MediasExport implements FromQuery
{

  use Exportable;

  protected $medias;

  public function __construct($medias)
  {
      $this->medias = $medias;
  }

  public function query()
  {
      return Media::query()->whereKey($this->medias);
  }
}
