<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLocation extends Model
{
    use HasFactory;
    public function media()
    {
        return $this->hasMany(Media::class, 'location_id');
    }
}
