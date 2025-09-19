<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_Item extends Model
{
  use HasFactory;
  protected $fillable = ['cart_id', 'product_id', 'price', 'quantity', 'vat'];
  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
  public function cart()
  {
    return $this->belongsTo(Cart::class, 'cart_id');
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
