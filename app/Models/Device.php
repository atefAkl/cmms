<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['name', 'description'];

    public function systemDevices()
    {
        return $this->hasMany(SystemDevice::class);
    }
}
