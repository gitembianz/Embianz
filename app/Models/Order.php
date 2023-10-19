<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'session_id', 'account_id', 'cart_id', 'quantity_amount', 'sum_amount', 'currency_id', 'status', 'delivery_method'];

  public function orders()
  {
    return $this->hasMany(Order_Item::class, 'order_id');
  }
  public function invoices()
  {
    return $this->hasMany(Invoice::class, 'order_id');
  }
  public function cart()
  {
    return $this->belongsTo(Cart::class, 'cart_id');
  }
  public function account()
  {
    return $this->belongsTo(Account::class, 'account_id');
  }
  public function currency()
  {
    return $this->belongsTo(Currency::class, 'currency_id');
  }
  public function status()
  {
    return $this->belongsTo(Status::class);
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
