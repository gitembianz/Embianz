<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'status', 'iso_code', 'country_id'];
  public function country()
  {
    return $this->belongsTo(Country::class, 'country_id');
  }
  public function cities()
  {
    return $this->hasMany(City::class, 'county_id');
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
