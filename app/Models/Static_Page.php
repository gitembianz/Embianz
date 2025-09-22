<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Static_Page extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'active',
    'content',
    'route',
    'sequence',
    'description',
    'display_in_footer',
    'created_by',
    'last_modified_by',
    'active'

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
