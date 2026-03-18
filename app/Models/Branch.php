<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'website',
        'logo',
        'favicon',
        'timezone',
        'currency',
        'language',
        'date_format',
        'time_format',
        'date_time_format',
    ];
}
