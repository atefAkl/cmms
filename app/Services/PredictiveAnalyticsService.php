<?php

namespace App\Services;

use App\Models\Room;
use App\Models\TemperatureReading;
use App\Models\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PredictiveAnalyticsService
{
    /**
     * Analyze a room for suspicious temperature trends.
     * Logic: If last 3 readings show a continuous rise > 0.2C each, 
     * and the system is active, trigger a warning.
     */
    public function analyzeGradient(Room $room)
    {
        $readings = TemperatureReading::where('room_id', $room->id)
            ->latest('recorded_at')
            ->take(4)
            ->get()
            ->reverse()
            ->values();

        if ($readings->count() < 3) return;

        $isRising = true;
        $totalRise = 0;

        for ($i = 1; $i < $readings->count(); $i++) {
            $diff = $readings[$i]->temperature - $readings[$i-1]->temperature;
            if ($diff <= 0.1) { // Threshold for "rising"
                $isRising = false;
                break;
            }
            $totalRise += $diff;
        }

        if ($isRising && $totalRise >= 0.5) {
            $this->createPredictiveAlert($room, "Suspicious temperature rise detected (+{$totalRise}°C over last readings). Potential door open or leak.");
        }
    }

    /**
     * Analyze cooling efficiency.
     * Logic: Measure time taken to drop temperature by 1C.
     */
    public function analyzeEfficiency(Room $room)
    {
        // Simplified: Log warning if target temp isn't reached within reasonable time after a rise
        $latest = $room->sensors()->latest()->first();
        if ($latest && $latest->temperature > $room->target_temperature + 2) {
            $lastActiveDuration = TemperatureReading::where('room_id', $room->id)
                ->where('temperature', '>', $room->target_temperature)
                ->where('created_at', '>', now()->subHours(2))
                ->count();
            
            if ($lastActiveDuration > 6) { // Assuming 10-min intervals, 1 hour of sustained high temp
                 $this->createPredictiveAlert($room, "Cooling efficiency degradation suspected. System unable to reach target setpoint for >1hr.", 'medium');
            }
        }
    }

    protected function createPredictiveAlert(Room $room, string $message, string $severity = 'low')
    {
        // Check if an open predictive alert already exists to avoid spam
        $exists = Alert::where('room_id', $room->id)
            ->where('status', 'open')
            ->where('message', 'LIKE', '%Suspicious%')
            ->exists();

        if ($exists) return;

        Alert::create([
            'room_id' => $room->id,
            'temperature' => $room->sensors()->latest()->first()->temperature ?? 0,
            'threshold' => $room->max_temperature,
            'severity' => $severity,
            'status' => 'open',
            'message' => $message,
            'source' => 'system_analytics'
        ]);

        Log::info("Predictive Alert Generated for Room {$room->id}: {$message}");
    }
}
