<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTask extends Model
{
    /** @use HasFactory<\Database\Factories\MaintenanceTaskFactory> */
    use HasFactory;

    protected $fillable = ['room_id', 'compressor_id', 'issue_description', 'root_cause', 'repair_action', 'technician_id', 'status', 'cost'];

    public function room() { return $this->belongsTo(\App\Models\Room::class); }
    public function compressor() { return $this->belongsTo(\App\Models\Compressor::class); }
    public function technician() { return $this->belongsTo(\App\Models\User::class, 'technician_id'); }
    public function parts() { return $this->hasMany(MaintenancePart::class); }
}
