<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function product_categories()
    {
        return $this->hasMany(Products_categories::class, 'category_id');
    }


    protected $fillable = [
        'name',
        'parrent',
        'long_description',
        'short_description',
        'sequence',
        'start_date',
        'end_date',
        'createdby',
        'lastmodifiedby',
        'seo_title',
    ];
}
