<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    public function tabel()
    {
        return $this->belongsTo(Tabels::class, 'tabel_id');
    }

    public function location()
    {
        return $this->belongsTo(MediaLocation::class, 'location_id');
    }
}
