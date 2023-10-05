<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juridic extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id', 'first_name', 'bank_name', 'account', 'last_name', 'phone', 'email', 'company_name', 'registration_code', 'registration_number', 'address1', 'address2', 'country', 'county', 'city', 'zipcode', 'type'
    ];
}
