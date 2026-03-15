<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_type',
        'equipment_id',
        'description',
        'interval_days',
        'priority',
        'estimated_duration',
        'last_performed',
        'next_due',
    ];

    protected $casts = [
        'last_performed' => 'date',
        'next_due' => 'date',
    ];

    public function equipment()
    {
        return $this->morphTo();
    }
}
