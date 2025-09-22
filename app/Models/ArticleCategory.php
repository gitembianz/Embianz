<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
  use HasFactory;
  public function article_categories()
  {
    return $this->hasMany(ArticleCategoryLink::class, 'category_id');
  }

  public static function search($search)
  {
    $query = static::query();

    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        foreach ((new static)->getFillable() as $field) {
          $q->orWhere($field, 'like', '%' . $search . '%');
        }
      });
    }

    return $query;
  }

  protected $fillable = [
    'name',
    'active',
    'short_description',
    'start_date',
    'end_date',
    'seo_title',
    'seo_id',
    'created_by',
    'last_modified_by'
  ];
}
