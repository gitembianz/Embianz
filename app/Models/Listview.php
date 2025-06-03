<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listview extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'model', 'logic', 'name', 'columns', 'filters', 'sorts','created_at', 'updated_at'];
    protected $casts = [
    'columns' => 'array',
    'filters' => 'array',
    'sorts' => 'array',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
