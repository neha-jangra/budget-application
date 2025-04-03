<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineItemDailyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rate',
        'currency',
        'rate_applicable_from'
    ];

    protected $casts = [
        'rate_applicable_from' => 'datetime',
    ];
}
