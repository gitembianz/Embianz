<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
  use HasFactory;
  public function status()
  {
    return $this->belongsTo(Status::class, 'status_id');
  }
  public static function search($search)
  {

    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('name', 'like', '%' . $search . '%')
      ->orWhere('code', 'like', '%' . $search . '%')
      ->orWhere('percent', 'like', '%' . $search . '%');
  }
}
