<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricelistEntries extends Model
{
  use HasFactory;
  public function pricelist()
  {
    return $this->belongsTo(PriceList::class, 'pricelist_id');
  }

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
}
