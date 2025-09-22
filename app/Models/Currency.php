<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'symbol',
    'code',
    'createdby',
    'lastmodifiedby'
  ];
  public function pricelist()
  {
    return $this->hasMany(PriceList::class, 'currency_id');
  }
  public function carts()
  {
    return $this->hasMany(Cart::class, 'currency_id');
  }
  public function orders()
  {
    return $this->belongsTo(Order::class, 'currency_id');
  }
  public function base_currencies()
  {
    return $this->hasMany(Exchange::class, 'base_currency_id');
  }
  public function quote_currencies()
  {
    return $this->hasMany(Exchange::class, 'quote_currency_id');
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
