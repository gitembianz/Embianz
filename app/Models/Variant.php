<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name'
    ];
    public function reference()
    {
        return $this->hasMany(ProductVariant::class, 'variant_id');
    }
}
