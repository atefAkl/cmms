<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Added this line
use Illuminate\Database\Eloquent\Model;

class Compressor extends Model
{
    /** @use HasFactory<\Database\Factories\CompressorFactory> */
    use HasFactory;

    protected $fillable = ['room_id', 'name', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
