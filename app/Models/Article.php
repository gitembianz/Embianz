<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'active', 'short_description', 'long_description', 'meta_description', 'start_date', 'end_date', 'seo_id', 'seo_title', 'created_by', 'last_modified_by'];

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
  public function media()
  {
    return $this->morphToMany(Media::class, 'mediable', 'item_media');
  }
  public function article_categories()
  {
    return $this->hasMany(ArticleCategoryLink::class, 'article_id');
  }
}
