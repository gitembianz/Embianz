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
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('name', 'like', '%' . $search . '%')
      ->orWhere('short_description', 'like', '%' . $search . '%')
      ->orWhere('start_date', 'like', '%' . $search . '%')
      ->orWhere('end_date', 'like', '%' . $search . '%')
      ->orWhere('seo_title', 'like', '%' . $search . '%')
      ->orWhere('seo_id', 'like', '%' . $search . '%')
      ->orWhere('created_by', 'like', '%' . $search . '%')
      ->orWhere('last_modified_by', 'like', '%' . $search . '%');
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
