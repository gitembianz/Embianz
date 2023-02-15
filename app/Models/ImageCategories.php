<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ImageCategories extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->cascadeOnDelete();
    }
    protected $fillable = [
        'category_id',
        'img_main_path',
        'img_search_path',
        'img_sequence',
    ];
}
