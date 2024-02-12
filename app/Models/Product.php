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
    return $this->hasMany(Related_Products::class, 'parrent_id');
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

  protected $fillable = [
    'name',
    'sku',
    'ean',
    'active',
    'short_description',
    'long_description',
    'quantity',
    'start_date',
    'end_date',
    'created_by',
    'last_modified_by',
    'seo_title',
    'popularity',
    'seo_id'
  ];

  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('name', 'like', '%' . $search . '%')
      ->orWhere('short_description', 'like', '%' . $search . '%');
  }
  public static function name($search)
  {
    return empty($search) ? static::query()
      : static::query()->where('name', 'like', '%' . $search . '%');
  }
}
