<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class TemperatureReading extends Model
{
    /** @use HasFactory<\Database\Factories\TemperatureReadingFactory> */
    use HasFactory;

    protected $fillable = ['room_id', 'temperature', 'recorded_by', 'recorded_at'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function recorder()
    {
        return $this->belongsTo(\App\Models\User::class, 'recorded_by');
    }
}
