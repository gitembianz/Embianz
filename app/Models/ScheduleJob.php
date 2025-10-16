<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleJob extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'type',
    'status',
    'job_id',
    'error',
    'details',
    'finished_at',
  ];
  public function parent()
  {
    return $this->belongsTo(AllJob::class, 'job_id');
  }
}
