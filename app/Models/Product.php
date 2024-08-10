<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;
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
          'name' => strip_tags($category->name),
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
          'name' => strip_tags($parentCategory->name),
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
    'parent_id'
  ];

  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()
      ->where(function ($query) use ($search) {
        $searchTerms = explode(' ', $search);

        foreach ($searchTerms as $term) {
          $query->where(function ($subQuery) use ($term) {
            $subQuery->where('id', 'like', '%' . $term . '%')
              ->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('ean', 'like', '%' . $term . '%')
              ->orWhere('meta_description', 'like', '%' . $term . '%')
              ->orWhere('short_description', 'like', '%' . $term . '%')
              ->orWhere('sku', 'like', '%' . $term . '%');
          });
        }
      });
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