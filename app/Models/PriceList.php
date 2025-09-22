<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'active', 'currency_id', 'createdby', 'lastmodifiedby', 'created_at', 'updated_at'];


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
}
