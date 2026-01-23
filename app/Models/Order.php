<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;
  protected $fillable = ['order_number', 'name', 'session_id', 'comments', 'account_id', 'cart_id', 'quantity_amount', 'sum_amount', 'final_amount', 'delivery_price', 'delivery_price_vat', 'currency_id', 'status_id', 'payment_id', 'voucher_id', 'voucher_value', 'promotion_value', 'avg_cost', 'storno_date', 'invoice_series', 'external_storno_number', 'external_invoice_number', 'invoice_date', 'billing_id', 'shipping_id', 'subscribe','created_at', 'updated_at'];

  public function orders()
  {
    return $this->hasMany(Order_Item::class, 'order_id');
  }
  public function invoices()
  {
    return $this->hasMany(Invoice::class, 'order_id');
  }
  public function awbs()
  {
    return $this->hasMany(Awbs::class, 'order_id');
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
  public function billing()
  {
    return $this->belongsTo(Address::class, 'billing_id');
  }
  public function shipping()
  {
    return $this->belongsTo(Address::class, 'shipping_id');
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
  public function orderItemsSorted()
  {
    return $this->hasMany(Order_Item::class, 'order_id')
      ->join('products', 'order__items.product_id', '=', 'products.id')

      ->orderByRaw("
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM products_categories pc
                    WHERE pc.product_id = products.id
                    AND pc.primary_category = 1
                ) THEN 0

                WHEN EXISTS (
                    SELECT 1
                    FROM products_categories pc
                    WHERE pc.product_id = products.id
                ) THEN 1

                ELSE 2
            END
        ")

      ->orderByRaw("
            (
                SELECT MIN(pc.category_id)
                FROM products_categories pc
                WHERE pc.product_id = products.id
            )
        ")

      ->select('order__items.*');
  }
}
