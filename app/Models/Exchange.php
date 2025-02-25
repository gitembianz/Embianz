<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'base_currency_id',
        'quote_currency_id',
        'value',
        'created_by',
        'last_modified_by',
        'last_modified_date'

    ];

    public function base_currency()
    {
        return $this->belongsTo(Currency::class, 'base_currency_id');
    }
    public function quote_currency()
    {
        return $this->belongsTo(Currency::class, 'quote_currency_id');
    }
    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('value', 'like', '%' . $search . '%')
            ->orWhere('created_by', 'like', '%' . $search . '%')
            ->orWhere('last_modified_by', 'like', '%' . $search . '%')
            ->orWhere('last_modified_date', 'like', '%' . $search . '%');
    }
}