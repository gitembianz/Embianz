<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;


  protected $fillable = [
    'name',
    'sku',
    'ean',
    'active',
    'is_new',
    'short_description',
    'long_description',
    'meta_description',
    'quantity',
    'start_date',
    'end_date',
    'created_by',
    'last_modified_by',
    'seo_title',
    'popularity',
    'seo_id',
    'parent_id',
    'brand',
    'innerid',
    'comments'
  ];

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
              ->orWhere('ean', 'like', '%' . $term . '%')
              ->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('meta_description', 'like', '%' . $term . '%')
              ->orWhere('short_description', 'like', '%' . $term . '%')
              ->orWhere('sku', 'like', '%' . $term . '%')
              ->orWhereRaw("
                            EXISTS (
                                SELECT 1
                                FROM (
                                    SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                                    UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
                                    UNION ALL SELECT 9 UNION ALL SELECT 10
                                ) AS numbers,
                                products AS p
                                WHERE p.id = products.id
                                AND SOUNDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(p.name, ' ', numbers.n), ' ', -1)) = ?
                            )", [$soundexValue]);
          });
        }
      });
  }





  public function product_categories()
  {
    return $this->hasMany(Products_categories::class, 'product_id');
  }

  public function product_specs()
  {
    return $this->hasMany(Product_Spec::class, 'product_id');
  }

  public function related_product()
  {
    return $this->hasMany(Related_Products::class, 'parent_id');
  }

  public function variants()
  {
    return $this->hasMany(ProductVariant::class, 'parent_id');
  }

  public function parent()
  {
    return $this->belongsTo(Product::class, 'parent_id');
  }

  public function beeingvariants()
  {
    return $this->hasMany(ProductVariant::class, 'product_id');
  }

  public function product_prices()
  {
    return $this->hasMany(PricelistEntries::class, 'product_id');
  }

  public function wishlists()
  {
    return $this->hasMany(Wishlist::class, 'product_id');
  }
  public function reviews()
  {
    return $this->hasMany(ProductReviews::class, 'product_id');
  }

  public function carts_item()
  {
    return $this->hasMany(Cart_Item::class, 'product_id');
  }

  public function orders_item()
  {
    return $this->hasMany(Order_Item::class, 'product_id');
  }

  public function media()
  {
    return $this->morphToMany(Media::class, 'mediable', 'item_media');
  }

  public function getCategoryHierarchy()
  {
    if (app()->has('global_cache_data') && app('global_cache_data') === 'true') {
      $cachedCategories = app()->make('cached_categories');
      $productCategoryIds = $this->product_categories->pluck('category_id')->unique();

      $categories = $cachedCategories->filter(function ($category) use ($productCategoryIds) {
        return $productCategoryIds->contains($category->id);
      });

      if ($categories->isEmpty()) {
        return [];
      }

      $longestHierarchy = collect();

      foreach ($categories as $category) {
        if (app()->has('global_default_category')) {
          if ($category->id == app('global_default_category')) {
            continue;
          }
        }

        $currentHierarchy = collect([
          [
            'name' => $category->short_description ?? strip_tags($category->name),
            'slug' => $category->seo_id ?? $category->id,
          ],
        ]);

        $currentCategory = $category;

        while ($currentCategory->parent->isNotEmpty()) {
          $parentCategory = $cachedCategories->firstWhere('id', $currentCategory->parent->first()->category_parent_id);

          if (!$parentCategory) {
            break;
          }

          $currentHierarchy->push([
            'name' => $parentCategory->short_description ?? strip_tags($parentCategory->name),
            'slug' => $parentCategory->seo_id ?? $parentCategory->id,
          ]);

          $currentCategory = $parentCategory;
        }

        if ($currentHierarchy->count() > $longestHierarchy->count()) {
          $longestHierarchy = $currentHierarchy;
        }
      }

      return $longestHierarchy->reverse()->toArray();
    } else {


      $categories = $this->product_categories->pluck('category')->unique();

      if ($categories->isEmpty()) {
        return [];
      }

      $longestHierarchy = collect();

      foreach ($categories as $category) {
        if (app()->has('global_default_category')) {
          if ($category->id == app('global_default_category')) {
            continue;
          }
        }
        $currentHierarchy = collect([
          [
            'name' => $category->short_description ?? strip_tags($category->name),
            'slug' => $category->seo_id ?? $category->id,
          ],
        ]);

        $currentCategory = $category;

        while ($currentCategory->parent->isNotEmpty()) {
          $parentCategory = $currentCategory->parent->first()->category_parent;

          if (!$parentCategory) {
            break;
          }

          $currentHierarchy->push([
            'name' => $parentCategory->short_description ?? strip_tags($parentCategory->name),
            'slug' => $parentCategory->seo_id ?? $parentCategory->id,
          ]);

          $currentCategory = $parentCategory;
        }

        if ($currentHierarchy->count() > $longestHierarchy->count()) {
          $longestHierarchy = $currentHierarchy;
        }
      }

      return $longestHierarchy->reverse()->toArray();
    }
  }



  public static function name($search)
  {
    return empty($search) ? static::query()
      : static::query()
      ->where(function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%')
          ->orWhere('ean', 'like', '%' . $search . '%')
          ->orWhere('short_description', 'like', '%' . $search . '%')
          ->orWhere('sku', 'like', '%' . $search . '%');
      });
  }
}
