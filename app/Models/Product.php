<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->hasMany(Products_categories::class, 'product_id');
    }

    protected $fillable = [
        'name',
        'short_description',
        'long_description',
        'quantity',
        'product_status',
        'start_date',
        'end_date',
        'createdby',
        'lastmodifiedby',
        'seo_title'
    ];

}
