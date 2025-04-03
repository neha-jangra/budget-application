<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'exact_id',
        'first_name',
        'last_name',
        'full_name',
        'email',
        'mobile',
        'country',
        'rate_type',
        'rate',
        'exact_id',
        'hourly_rate',
        'start_rate_date',
        'end_rate_date'
    ];
}
