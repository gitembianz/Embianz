<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    public function location()
    {
        return $this->belongsTo(MediaLocation::class, 'location_id');
    }
}
