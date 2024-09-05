<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specs extends Model
{
  use HasFactory;

  protected $table = 'specs';

  protected $fillable = [
    'name',
    'um',
    'sequence', 'mark_as_filter'
  ];
  public static function search($search)
  {
    return empty($search) ? static::query()
      : static::query()->where('id', 'like', '%' . $search . '%')
      ->orWhere('name', 'like', '%' . $search . '%')
      ->orWhere('um', 'like', '%' . $search . '%')
      ->orWhere('sequence', 'like', '%' . $search . '%')
      ->orWhere('created_at', 'like', '%' . $search . '%');
  }

  public function product_spec()
  {
    return $this->hasMany(Product_Spec::class, 'spec_id');
  }
}
