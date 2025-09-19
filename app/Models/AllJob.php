<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllJob extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'type',
    'status',
    'related_table',
    'related_id',
    'payload',
    'meta',
    'error',
    'progress',
    'started_at',
    'finished_at',
    'created_at',
    'updated_at',
    'is_recurring',
    'recurrence_rule',
    'next_run_at',
    'active'
  ];

  protected $casts = [
    'payload' => 'array'
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
