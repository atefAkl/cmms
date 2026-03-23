<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemperatureProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'min_temp',
        'max_temp',
        'target_temp',
        'tolerance',
        'product_type',
    ];

    public function assignments()
    {
        return $this->hasMany(RoomTemperatureProfileAssignment::class);
    }
}
