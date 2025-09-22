<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
  use HasFactory;

  protected $fillable = [
    'base_currency_id',
    'quote_currency_id',
    'value',
    'date',
    'created_by',
    'last_modified_by',
  ];

  public function base_currency()
  {
    return $this->belongsTo(Currency::class, 'base_currency_id');
  }
  public function quote_currency()
  {
    return $this->belongsTo(Currency::class, 'quote_currency_id');
  }
  public function suppliers()
  {
    return $this->hasMany(Order_Supplier::class, 'exchange_id');
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
