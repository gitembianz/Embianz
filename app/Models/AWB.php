<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AWB extends Model
{
    use HasFactory;
    protected $fillable = ['order_id', 'type', 'path', 'date'];
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}