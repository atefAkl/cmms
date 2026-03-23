<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTemperatureProfileAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'temperature_profile_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function profile()
    {
        return $this->belongsTo(TemperatureProfile::class, 'temperature_profile_id');
    }
}
