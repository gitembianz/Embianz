<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Supplier extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'supplier_name', 'status', 'date', 'currency', 'exchange_id', 'quote_currency', 'final_amount', 'final_amount_quote_currency', 'vat_sum_amount', 'sum_amount', 'created_by', 'last_modified_by'];

  public function items()
  {
    return $this->hasMany(Order_Supplier_Item::class, 'order__supplier_id');
  }
  public function exchange()
  {
    return $this->belongsTo(Exchange::class, 'exchange_id');
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
  public function currency_info()
{
    return $this->belongsTo(Currency::class, 'currency', 'name');
}
  
}
