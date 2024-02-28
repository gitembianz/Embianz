<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomScript extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'content',
        'active'
    ];
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('type', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('content', 'like', '%' . $search . '%');
    }
}
