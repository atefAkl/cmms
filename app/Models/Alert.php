<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    /** @use HasFactory<\Database\Factories\AlertFactory> */
    use HasFactory;

    protected $fillable = ['room_id', 'temperature', 'threshold', 'severity', 'status', 'resolved_at'];

    public function room()
    {
        return $this->belongsTo(\App\Models\Room::class);
    }
}
