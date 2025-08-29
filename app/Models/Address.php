<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = ['account_id','is_default', 'type', 'first_name', 'last_name', 'phone', 'email', 'address1', 'address2', 'country', 'country_iso', 'county', 'county_iso', 'city', 'zipcode', 'updated_at'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('first_name', 'like', '%' . $search . '%')
            ->orWhere('last_name', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('address1', 'like', '%' . $search . '%')
            ->orWhere('address2', 'like', '%' . $search . '%')
            ->orWhere('country', 'like', '%' . $search . '%')
            ->orWhere('county', 'like', '%' . $search . '%')
            ->orWhere('city', 'like', '%' . $search . '%')
            ->orWhere('zipcode', 'like', '%' . $search . '%')
            ->orWhere('type', 'like', '%' . $search . '%');
    }
}
