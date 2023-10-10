<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  protected $fillable = ['session_id', 'quantity_amount', 'sum_amount', 'currency_id', 'status'];

  public function orders()
  {
    return $this->hasMany(Order_Item::class, 'order_id');
  }
  public function currency()
  {
    return $this->belongsTo(Currency::class, 'currency_id');
  }
}
