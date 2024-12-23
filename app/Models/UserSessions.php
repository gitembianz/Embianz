<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSessions extends Model
{
    use HasFactory;

    public function promotions()
    {
        return $this->hasMany(UserPromotions::class, 'session_id');
    }
}