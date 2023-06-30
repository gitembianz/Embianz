<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Spec extends Model
{
    use HasFactory;

    public function spec()
    {
        return $this->belongsTo(Specs::class, 'spec_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
