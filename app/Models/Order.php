<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'order_number', 'session_id', 'account_id', 'cart_id', 'voucher_id', 'voucher_value', 'delivery_price', 'final_amount', 'quantity_amount', 'sum_amount', 'currency_id', 'status_id', 'payment_id'];

  public function orders()
  {
    return $this->hasMany(Order_Item::class, 'order_id');
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
  public function payment()
  {
    return $this->belongsTo(Payment::class);
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
      ->orWhere('status', 'like', '%' . $search . '%')
      ->orWhere('sum_amount', 'like', '%' . $search . '%');
  }
}