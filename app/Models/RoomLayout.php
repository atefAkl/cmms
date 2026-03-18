<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomLayout extends Model
{

    // softDeletes
    use SoftDeletes;

    protected $table = 'room_layouts';
    protected $fillable = [
        'name',
        'slug',
        'image',
        'layout_dimensions',
        'door_dimensions',
        'door_position',
        'wall_thickness',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public $timestamps = true;

    protected $casts = [
        'is_active' => 'boolean',
        'layout_dimensions' => 'array',
        'door_dimensions' => 'array',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
