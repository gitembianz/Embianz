<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCost extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    protected $fillable = [
        'product_id',
        'price',
        'cost',
        'date',
        'created_by',
        'last_modified_by'
    ];
}