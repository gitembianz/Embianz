<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function product_categories()
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

    public static function search($search) {
      return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('short_description', 'like', '%'.$search.'%');
    }
}
