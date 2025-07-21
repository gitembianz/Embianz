<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllJob extends Model
{
    use HasFactory;
      protected $fillable = [
        'name',
        'type',
        'status',
        'payload',
        'error',
        'related_table',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'payload' => 'array'
    ];
}
