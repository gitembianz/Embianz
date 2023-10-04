<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id', 'first_name', 'last_name', 'phone', 'email', 'address1', 'address2', 'country', 'county', 'city', 'zipcode', 'type'
    ];
}
