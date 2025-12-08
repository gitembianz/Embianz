<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReviews extends Model
{
  use HasFactory;
  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
  protected $fillable = [
    'product_id',
    'acronim',
    'score',
    'comment',
    'approved',
  ];
public static function search($search)
{
    $query = static::query();

    if (!empty($search)) {
        $query->where(function ($q) use ($search) {

            foreach ((new static)->getFillable() as $field) {
                $q->orWhere($field, 'like', '%' . $search . '%');
            }

            $q->orWhereHas('product', function ($subQuery) use ($search) {
                $subQuery->where('name', 'LIKE', '%' . $search . '%');
            });

        });
    }

    return $query;
}

}
