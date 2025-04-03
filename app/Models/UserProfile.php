<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = ['company', 'gender', 'address', 'city', 'state', 'pin_code', 'country', 'user_id', 'first_name', 'last_name', 'position', 'rate', 'country_code', 'country_rate', 'project_code', 'photo'];
}
