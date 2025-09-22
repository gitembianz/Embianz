<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'type', 'details', 'promotion_percent', 'promotion_value', 'start_date', 'end_date', 'cooldown_timer', 'cart_amount', 'cookieid', 'active'];

  public function sessions()
  {
    return $this->hasMany(UserSessions::class, 'session_id');
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
