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

  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('parameter', 'like', '%' . $search . '%')
      ->orWhere('value', 'like', '%' . $search . '%')
      ->orWhere('description', 'like', '%' . $search . '%')
      ->orWhere('created_at', 'like', '%' . $search . '%');
  }
}
