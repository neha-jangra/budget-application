<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactSync extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'execute_at',
        'executed_at',
        'last_synced_at',
    ];

    protected $casts = [
        'execute_at' => 'datetime',
        'executed_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

}
