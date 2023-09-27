<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;
  protected $table = 'carts';
  protected $fillable = ['session_id', 'quantity_amount', 'sum_amount'];

  public function carts()
  {
    return $this->hasMany(Cart_Item::class, 'cart_id');
  }
}
