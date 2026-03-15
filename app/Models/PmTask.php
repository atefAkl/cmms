<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PmTask extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_SKIPPED = 'skipped';

    protected $fillable = [
        'pm_schedule_id',
        'scheduled_date',
        'status',
        'assigned_to',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(PmSchedule::class, 'pm_schedule_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
