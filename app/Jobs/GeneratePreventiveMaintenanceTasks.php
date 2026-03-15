<?php

namespace App\Jobs;

use App\Models\PmSchedule;
use App\Models\Room;
use App\Models\Compressor;
use App\Models\Evaporator;
use App\Models\MaintenanceTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

class GeneratePreventiveMaintenanceTasks implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = Carbon::today();

        // Find schedules where next_due is today or earlier
        $dueSchedules = PmSchedule::where('next_due', '<=', $today)->get();

        foreach ($dueSchedules as $schedule) {
            // Determine Room ID and Compressor ID based on the morph
            $roomId = null;
            $compressorId = null;

            if ($schedule->equipment_type === Room::class) {
                $roomId = $schedule->equipment_id;
            } elseif ($schedule->equipment_type === Compressor::class) {
                $compressorId = $schedule->equipment_id;
                // Attempt to find associated room
                $compressor = Compressor::find($compressorId);
                $roomId = $compressor ? $compressor->room_id : null;
            } elseif ($schedule->equipment_type === Evaporator::class) {
                $evap = Evaporator::find($schedule->equipment_id);
                $roomId = $evap ? $evap->room_id : null;
            }

            // Create maintenance task
            MaintenanceTask::create([
                'room_id' => $roomId,
                'compressor_id' => $compressorId,
                'issue_description' => '[PM] ' . $schedule->description . ' (Priority: ' . $schedule->priority . ')',
                'status' => 'open', // assuming 'open' is the default
                // other fields are left null until assessed
            ]);

            // Update schedule
            $schedule->update([
                'last_performed' => $today,
                'next_due' => $today->copy()->addDays($schedule->interval_days),
            ]);
        }
    }
}
