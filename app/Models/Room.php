<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;

    protected $fillable = ['name', 'location', 'target_temperature', 'min_temperature', 'max_temperature'];

    public function refrigerationSystems()
    {
        return $this->hasMany(RefrigerationSystem::class);
    }

    public function sensors()
    {
        return $this->hasMany(TemperatureReading::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouses', 'location', 'id');
    }
}
