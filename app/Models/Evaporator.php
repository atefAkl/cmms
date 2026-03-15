<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class Evaporator extends Model
{
    /** @use HasFactory<\Database\Factories\EvaporatorFactory> */
    use HasFactory;

    protected $fillable = ['room_id', 'refrigeration_system_id', 'fan_count', 'heater_count', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function refrigerationSystem()
    {
        return $this->belongsTo(RefrigerationSystem::class);
    }
}
