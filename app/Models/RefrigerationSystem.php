<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefrigerationSystem extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'name', 'status', 'installed_at', 'notes'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function topLevelAssets()
    {
        return $this->hasMany(Asset::class)->whereNull('parent_id');
    }

    public function temperatureReadings()
    {
        return $this->hasMany(TemperatureReading::class);
    }
}
