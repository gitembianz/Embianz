<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPromotions extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id',
        'promotion_id',
        'promotion_type',
        'promotion_cookieid',
        'promotion_start_date',
        'promotion_cooldown_timer',
        'promotion_expiration_date',
        'promotion_value',
        'promotion_percent',
        'promotion_cart_amount'
    ];
    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}