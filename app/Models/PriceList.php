<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
  use HasFactory;

  public function currency()
  {
    return $this->belongsTo(Currency::class, 'currency_id');
  }

  public function pricelistentries()
  {
    return $this->hasMany(PricelistEntries::class, 'pricelist_id');
  }

  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('name', 'like', '%' . $search . '%')
      ->orWhere('created_at', 'like', '%' . $search . '%');
  }
}
