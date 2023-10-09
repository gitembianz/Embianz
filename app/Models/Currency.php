<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
  use HasFactory;
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
}
