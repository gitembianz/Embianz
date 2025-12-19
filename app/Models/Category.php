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
    return $this->hasMany(Subcategory::class, 'parent_id');
  }
  public function parent()
  {
    return $this->hasMany(Subcategory::class, 'category_id');
  }
  public function media()
  {
    return $this->morphToMany(Media::class, 'mediable', 'item_media');
  }

  // public function getCategoryBreadcrumbs()
  // {
  //   $breadcrumbs = collect();

  //   $currentCategory = $this;

  //   while ($currentCategory) {
  //     $breadcrumbs->prepend([
  //       'name' => $currentCategory->short_description ?? strip_tags($currentCategory->name),
  //       'slug' => $currentCategory->seo_id ?? $currentCategory->id,
  //     ]);

  //     if ($currentCategory->parent->isNotEmpty()) {
  //       $parentCategory = $currentCategory->parent->first()->category_parent;

  //       if (!$parentCategory) {
  //         break;
  //       }

  //       $currentCategory = $parentCategory;
  //     } else {
  //       break;
  //     }
  //   }

  //   return $breadcrumbs->toArray();
  // }

  protected $fillable = [
    'name',
    'accepted_items',
    'active',
    'slider_sequence',
    'has_parent',
    'preload_image',
    'one_product_page_category',
    'display_variant_price',
    'long_description',
    'long_description_bottom',
    'short_description',
    'meta_description',
    'sequence',
    'start_date',
    'end_date',
    'store_tab',
    'seo_title',
    'seo_id',
    'createdby',
    'lastmodifiedby',
  ];

  public static function panel_search($search)
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

  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()
      ->where(function ($query) use ($search) {
        $searchTerms = explode(' ', $search);

        foreach ($searchTerms as $term) {
          $soundexValue = soundex($term);

          $query->where(function ($subQuery) use ($term, $soundexValue) {
            $subQuery->where('id', 'like', '%' . $term . '%')
              ->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('short_description', 'like', '%' . $term . '%')
              ->orWhereRaw("
                            EXISTS (
                                SELECT 1
                                FROM (
                                    SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                                    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8
                                    UNION ALL SELECT 9 UNION ALL SELECT 10
                                ) AS numbers,
                               categories AS p
                                WHERE p.id = categories.id
                                AND SOUNDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(p.name, ' ', numbers.n), ' ', -1)) = ?
                            )", [$soundexValue]);
          });
        }
      });
  }
}
