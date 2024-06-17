<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $fillable = [
        'parrent_id',
        'product_id',
        'variant_id',
        'value',
        'displayed'
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
