<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'code', 'percent', 'value', 'status_id', 'single_use', 'start_cate', 'end_date'];
  public function status()
  {
    return $this->belongsTo(Status::class, 'status_id');
  }
  public static function search($search)
  {
    $query = static::query();

    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        foreach ((new static)->getFillable() as $field) {
          $q->orWhere($field, 'like', '%' . $search . '%');
        }
      });
    }

    return $query;
  }
}
