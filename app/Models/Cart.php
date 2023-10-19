<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;
  protected $table = 'carts';
  protected $fillable = ['session_id', 'name', 'quantity_amount', 'sum_amount', 'status', 'currency_id'];

  public function carts()
  {
    return $this->hasMany(Cart_Item::class, 'cart_id');
  }
  public function currency()
  {
    return $this->belongsTo(Currency::class, 'currency_id');
  }
  public function order()
  {
    return $this->hasOne(Order::class, 'cart_id');
  }
  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('session_id', 'like', '%' . $search . '%')
      ->orWhere('quantity_amount', 'like', '%' . $search . '%')
      ->orWhere('status', 'like', '%' . $search . '%')
      ->orWhere('sum_amount', 'like', '%' . $search . '%');
  }
}
