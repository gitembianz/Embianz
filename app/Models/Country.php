<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('iso_code3', 'like', '%' . $search . '%')
            ->orWhere('phone_code', 'like', '%' . $search . '%')
            ->orWhere('currency', 'like', '%' . $search . '%')
            ->orWhere('iso_code', 'like', '%' . $search . '%');
    }
}