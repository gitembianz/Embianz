<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'details'];
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public static function search($search)
    {
        return empty($search) ? static::query()
            : static::query()->where('id', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('details', 'like', '%' . $search . '%')
            ->orWhere('voucher_code', 'like', '%' . $search . '%');
    }
}