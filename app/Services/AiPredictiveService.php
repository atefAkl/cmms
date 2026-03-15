<?php

namespace App\Services;

use App\Models\Room;
use App\Models\TemperatureReading;
use App\Models\MaintenanceTask;
use App\Models\PmSchedule;
use Illuminate\Support\Facades\Log;

class AiPredictiveService
{
    /**
     * Analyzes temperature gradients for a room to predict equipment failure.
     */
    public function analyzeRoomGradients(Room $room)
    {
        // Get last 10 readings for this room
        $readings = TemperatureReading::where('room_id', $room->id)
            ->latest('recorded_at')
            ->take(10)
            ->get()
            ->reverse();

        if ($readings->count() < 5) {
            return;
        }

        // Calculate gradient (rate of change)
        $gradients = [];
        $previousReading = null;

        foreach ($readings as $reading) {
            if ($previousReading) {
                $tempDiff = $reading->temperature - $previousReading->temperature;
                // Simplified gradient: temperature change per reading
                $gradients[] = $tempDiff;
            }
            $previousReading = $reading;
        }

        // Calculate average gradient in the last few readings
        $avgGradient = array_sum($gradients) / count($gradients);

        // If temperature is rising steadily (gradient > 0.5 degrees per log interval) 
        // while still below threshold, it could be a sign of efficiency loss.
        if ($avgGradient > 0.5) {
            $this->triggerPredictiveAction($room, "Abnormal temperature rise detected (Avg Gradient: {$avgGradient}). Possible cooling system efficiency loss.");
        }
    }

    /**
     * Takes action based on predictive analysis results.
     */
    protected function triggerPredictiveAction(Room $room, $reason)
    {
        Log::warning("PREDICTIVE ALERT for Room {$room->id}: {$reason}");

        // Create a maintenance task with 'open' status if not already exists for the same issue
        $existingTask = MaintenanceTask::where('room_id', $room->id)
            ->where('status', 'open')
            ->where('issue_description', 'LIKE', '%Predictive%')
            ->first();

        if (!$existingTask) {
            MaintenanceTask::create([
                'room_id' => $room->id,
                'issue_description' => "Predictive Maintenance: {$reason}",
                'status' => MaintenanceTask::STATUS_OPEN,
                'priority' => 'high' // Assuming priority exists or we use status/logic
            ]);
        }
        
        // Also update any pending PM schedules to 'high' priority
        PmSchedule::where('equipment_type', Room::class)
            ->where('equipment_id', $room->id)
            ->update(['priority' => 'high']);
    }
}
