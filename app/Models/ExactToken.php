<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExactToken extends Model
{
    use HasFactory;

    protected $fillable = ['access_token', 'refresh_token', 'token_type', 'api_response'];
}
