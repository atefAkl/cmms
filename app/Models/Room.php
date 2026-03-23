<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    /** @use HasFactory<\Database\Factories\RoomFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'warehouse_id',
        'room_layout_id',
        'created_by',
        'updated_by',
        'status',
        'is_active'
    ];

    public $timestamps = true;

    protected $casts = [
        'warehouse_id' => 'integer',
        'room_layout_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'status' => 'string',
        'is_active' => 'boolean',
    ];

    public function refrigerationSystems()
    {
        return $this->hasMany(RefrigerationSystem::class);
    }

    public function coolingSystems()
    {
        return $this->hasMany(RefrigerationSystem::class);
    }

    public function sensors()
    {
        return $this->hasMany(TemperatureReading::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class , 'warehouse_id', 'id');
    }

    public function layout()
    {
        return $this->belongsTo(RoomLayout::class , 'room_layout_id', 'id');
    }

    public function profileAssignments()
    {
        return $this->hasMany(RoomTemperatureProfileAssignment::class);
    }

    public function activeProfileAssignment()
    {
        return $this->hasOne(RoomTemperatureProfileAssignment::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }
}
