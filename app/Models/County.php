<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status', 'iso_code', 'country_id'];
    public function country()
    {
        return $this->belongsTo(Country::class, 'countryid');
    }
}
