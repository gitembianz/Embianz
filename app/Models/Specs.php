<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specs extends Model
{
  use HasFactory;

  protected $table = 'specs';

  protected $fillable = [
    'name',
    'um',
    'sequence',
    'mark_as_filter'
  ];

  public function product_spec()
  {
    return $this->hasMany(Product_Spec::class, 'spec_id');
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
