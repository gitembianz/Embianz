<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  use HasFactory;
  protected $fillable = ['order_id', 'account_id', 'date', 'type', 'path'];
  public function account()
  {
    return $this->belongsTo(Account::class, 'account_id');
  }
  public function order()
  {
    return $this->belongsTo(Order::class, 'order_id');
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
