<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store_Settings extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'parameter',
    'value',
    'description',
    'createdby',
    'lastmodifiedby',
    'created_at',
    'updated_at'

  ];
}
