<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class TemperatureReading extends Model
{
    /** @use HasFactory<\Database\Factories\TemperatureReadingFactory> */
    use HasFactory;

    protected $fillable = ['room_id', 'refrigeration_system_id', 'temperature', 'humidity', 'save_status_snapshot', 'registered_by', 'recorded_by', 'recorded_at'];

    protected $casts = [
        'recorded_at' => 'datetime',
        'save_status_snapshot' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function recorder()
    {
        return $this->belongsTo(\App\Models\User::class, 'recorded_by');
    }

    public function registeredBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'registered_by');
    }

    public function refrigerationSystem()
    {
        return $this->belongsTo(RefrigerationSystem::class);
    }
}
