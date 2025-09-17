<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReviews extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    protected $fillable = [
        'product_id',
        'count',
        'value'
    ];
     public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('acronim', 'like', '%' . $search . '%')
            ->orWhere('score', 'like', '%' . $search . '%')
            ->orWhere('commnent', 'like', '%' . $search . '%');
    }
}
