<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'exact_id',
        'account_id',
        'code',
        'email',
        'account_name',
        'phone',
        'city',
        'state',
        'country',
        'address'
    ];
}
