<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecGroup extends Model
{
  use HasFactory;
  public function spec()
  {
    return $this->hasMany(Specs::class, 'group_id');
  }
}
