<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;
  protected $table = 'carts';
  protected $fillable = ['name', 'session_id', 'quantity_amount', 'sum_amount', 'currency_id', 'status_id', 'final_amount', 'order_id', 'delivery_price', 'delivery_price_vat', 'voucher_id', 'voucher_value', 'promotion_value', 'seen_by_customer'];

  public function cartItems()
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
