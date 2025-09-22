<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvImportJob extends Model
{
  use HasFactory;
  protected $fillable = [
    'queue',
    'name',
    'type',
    'meta',
    'status',
    'started_at',
    'finished_at',
    'errors',
    'error_file'
  ];
  protected $casts = [
    'meta' => 'array',
    'started_at' => 'datetime',
    'finished_at' => 'datetime',
  ];
}
