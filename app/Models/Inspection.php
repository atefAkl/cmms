<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\Compressor;
use App\Models\InspectionItem;

class Inspection extends Model
{
    /** @use HasFactory<\Database\Factories\InspectionFactory> */
    use HasFactory;

    protected $fillable = ['room_id', 'compressor_id', 'inspector_id', 'date', 'result', 'notes'];

    public function room() { return $this->belongsTo(Room::class); }
    public function compressor() { return $this->belongsTo(Compressor::class); }
    public function inspector() { return $this->belongsTo(\App\Models\User::class, 'inspector_id'); }
    public function items() { return $this->hasMany(InspectionItem::class); }
}
