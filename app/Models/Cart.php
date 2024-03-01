<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;
  protected $table = 'carts';
  protected $fillable = ['session_id', 'voucher_value', 'final_amount', 'voucher_id', 'name', 'delivery_price', 'quantity_amount', 'sum_amount', 'status_id', 'order_id', 'currency_id'];

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
    return $this->belongsTo(Order::class, 'order_id');
  }
  public function status()
  {
    return $this->belongsTo(Status::class, 'status_id');
  }
  public function voucher()
  {
    return $this->belongsTo(Voucher::class);
  }
  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('session_id', 'like', '%' . $search . '%')
      ->orWhere('quantity_amount', 'like', '%' . $search . '%')
      ->orWhere('status_id', 'like', '%' . $search . '%')
      ->orWhere('sum_amount', 'like', '%' . $search . '%');
  }
}
