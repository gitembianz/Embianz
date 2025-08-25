<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategoryLink extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
    protected $fillable = [
        'article_id',
        'category_id',
        'primary_article_category'
    ];
}
