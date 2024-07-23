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
  public function subcategory()
  {
    return $this->hasMany(Subcategory::class, 'parrent_id');
  }
  public function parrent()
  {
    return $this->hasMany(Subcategory::class, 'category_id');
  }
  public function media()
  {
    return $this->morphToMany(Media::class, 'mediable', 'item_media');
  }

  public function getCategoryBreadcrumbs()
  {
    $breadcrumbs = collect();

    $currentCategory = $this;

    while ($currentCategory) {
      $breadcrumbs->prepend([
        'name' => $currentCategory->short_description ?? $currentCategory->name,
        'slug' => $currentCategory->seo_id ?? $currentCategory->id,
      ]);

      if ($currentCategory->parrent->isNotEmpty()) {
        $parrentCategory = $currentCategory->parrent->first()->category_parrent;

        if (!$parrentCategory) {
          break;
        }

        $currentCategory = $parrentCategory;
      } else {
        break;
      }
    }

    return $breadcrumbs->toArray();
  }

  protected $fillable = [
    'name',
    'parrent',
    'active',
    'long_description',
    'meta_description',
    'short_description',
    'sequence',
    'slider_sequence',
    'start_date',
    'end_date',
    'createdby',
    'lastmodifiedby',
    'seo_title',
    'seo_id'
  ];

  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()
      ->where(function ($query) use ($search) {
        // Split the search string into individual words
        $searchTerms = explode(' ', $search);

        foreach ($searchTerms as $term) {
          $query->where(function ($subQuery) use ($term) {
            $subQuery->where('id', 'like', '%' . $term . '%')
              ->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('sequence', 'like', '%' . $term . '%')
              ->orWhere('short_description', 'like', '%' . $term . '%');
          });
        }
      });
  }

  public static function search_by_name($search)
  {
    return empty($search) ? static::query()
      : static::query()
      ->where(function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%')
          ->orWhere('short_description', 'like', '%' . $search . '%');
      });
  }
}