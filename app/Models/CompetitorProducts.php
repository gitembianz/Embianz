<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorProducts extends Model
{
  use HasFactory;
  public function competitor()
  {
    return $this->belongsTo(Competitor::class, 'competitor_id');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
  protected $fillable = [
    'name',
    'price',
    'url',
    'product_id',
    'internal_price',
    'competitor_id',
    'difference_value',
    'difference_percent',
    'created_by',
    'last_modified_by'
  ];
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
