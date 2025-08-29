<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'product_id',
        'variant_id',
        'value',
        'displayed',
        'default_variant'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function reference()
    {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}
