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
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('name', 'like', '%' . $search . '%')
      ->orWhere('short_description', 'like', '%' . $search . '%')
      ->orWhere('long_description', 'like', '%' . $search . '%')
      ->orWhere('meta_description', 'like', '%' . $search . '%')
      ->orWhere('start_date', 'like', '%' . $search . '%')
      ->orWhere('end_date', 'like', '%' . $search . '%')
      ->orWhere('seo_title', 'like', '%' . $search . '%')
      ->orWhere('seo_id', 'like', '%' . $search . '%')
      ->orWhere('created_by', 'like', '%' . $search . '%')
      ->orWhere('last_modified_by', 'like', '%' . $search . '%');
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
