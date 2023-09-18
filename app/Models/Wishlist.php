<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wishlist extends Model
{
  use HasFactory;
  protected $table = 'wishlist';
  protected $fillable = ['session_id', 'product_id'];

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
}