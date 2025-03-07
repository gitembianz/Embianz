<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Static_Page extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'content',
        'route',
        'sequence',
        'display_in_footer',
        'created_by',
        'last_modified_by'

    ];

    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('content', 'like', '%' . $search . '%')
            ->orWhere('route', 'like', '%' . $search . '%')
            ->orWhere('sequence', 'like', '%' . $search . '%')
            ->orWhere('created_by', 'like', '%' . $search . '%')
            ->orWhere('last_modified_by', 'like', '%' . $search . '%');
    }
}
