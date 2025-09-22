<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  use HasFactory;
  protected $fillable = ['account_id', 'first_name', 'last_name', 'phone', 'email', 'address1', 'address2', 'country', 'country_iso', 'country', 'county', 'county_iso', 'city', 'zipcode', 'type', 'is_default', 'updated_at'];

  public function account()
  {
    return $this->belongsTo(Account::class, 'account_id');
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
