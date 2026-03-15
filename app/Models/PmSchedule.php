<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmSchedule extends Model
{
    use HasFactory;

    public const FREQUENCY_DAILY = 'daily';
    public const FREQUENCY_WEEKLY = 'weekly';
    public const FREQUENCY_MONTHLY = 'monthly';
    public const FREQUENCY_QUARTERLY = 'quarterly';
    public const FREQUENCY_YEARLY = 'yearly';

    protected $fillable = [
        'title',
        'equipment_type',
        'equipment_id',
        'description',
        'frequency_type',
        'frequency_value',
        'priority',
        'estimated_duration',
        'last_performed',
        'next_due',
        'created_by',
    ];

    protected $casts = [
        'last_performed' => 'date',
        'next_due' => 'date',
    ];

    public function equipment()
    {
        return $this->morphTo();
    }

    public function tasks()
    {
        return $this->hasMany(PmTask::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
