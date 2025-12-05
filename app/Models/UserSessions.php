<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSessions extends Model
{
  use HasFactory;
  protected $fillable = [
    'sessions',
    'ip_address',
    'user_agent',
    'http_referer',
    'visited_url',
    'status',
    'country',
    'countryCode',
    'region',
    'regionName',
    'city',
    'zip',
    'lat',
    'lon',
    'timezone',
    'isp',
    'org',
    'as'
  ];

  public function promotions()
  {
    return $this->hasMany(UserPromotions::class, 'session_id');
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
