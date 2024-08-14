<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'first_name', 'last_name', 'phone', 'email', 'company_name', 'registration_code', 'registration_number', 'bank_name', 'account', 'updated_at'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'account_id');
    }
    public function addresses()
    {
        return $this->hasMany(Address::class, 'account_id');
    }
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('type', 'like', '%' . $search . '%')
            ->orWhere('first_name', 'like', '%' . $search . '%')
            ->orWhere('last_name', 'like', '%' . $search . '%')
            ->orWhere('phone', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('company_name', 'like', '%' . $search . '%')
            ->orWhere('registration_code', 'like', '%' . $search . '%')
            ->orWhere('registration_number', 'like', '%' . $search . '%')
            ->orWhere('bank_name', 'like', '%' . $search . '%')
            ->orWhere('account', 'like', '%' . $search . '%');
    }
}
