<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorProducts extends Model
{
    use HasFactory;
    public function competitor()
    {
        return $this->belongsTo(Competitor::class, 'competitor_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    protected $fillable = [
        'product_id',
        'competitor_id',
        'name',
        'url',
        'price',
        'internal_price',
        'difference_value',
        'difference_percentage',
        'created_by',
        'last_modified_by'
    ];

}
