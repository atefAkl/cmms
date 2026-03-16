<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemDevice extends Model
{
    protected $fillable = ['refrigeration_system_id', 'name', 'device_id', 'installed'];

    protected $casts = [
        'installed' => 'date',
    ];

    public function refrigerationSystem()
    {
        return $this->belongsTo(RefrigerationSystem::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'system_device_id');
    }
}
