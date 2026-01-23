<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  use HasFactory;
  protected $fillable = ['name', 'type', 'first_name', 'last_name', 'phone', 'email', 'company_name', 'registration_code', 'registration_number','bank_name', 'account', 'subscribe','updated_at'];

  public function orders()
  {
    return $this->hasMany(Order::class, 'account_id');
  }
  public function addresses()
  {
    return $this->hasMany(Address::class, 'account_id');
  }
  public function invoices()
  {
    return $this->hasMany(Invoice::class, 'account_id');
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
