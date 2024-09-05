<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'sequence'
    ];
    public function reference()
    {
        return $this->hasMany(ProductVariant::class, 'variant_id');
    }
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('sequence', 'like', '%' . $search . '%');
    }
}
