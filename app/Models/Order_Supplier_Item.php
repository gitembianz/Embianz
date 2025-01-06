<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Supplier_Item extends Model
{
    use HasFactory;
    public function order()
    {
        return $this->belongsTo(Order_Supplier::class, 'order__supplier_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
