<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status', 'date' . 'created_by', 'last_modified_by'];

    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orWhere('created_by', 'like', '%' . $search . '%')
            ->orWhere('last_modified_by', 'like', '%' . $search . '%');
    }
    public function items()
    {
        return $this->hasMany(Order_Supplier_Item::class, 'order__supplier_id');
    }
}
