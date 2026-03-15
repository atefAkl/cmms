<?php

namespace App\Services;

use App\Models\TemperatureReading;
use App\Models\Room;
use App\Events\TemperatureThresholdExceeded;

class TemperatureService
{
    public function logTemperature(Room $room, $temperature, $userId, $refSystemId = null)
    {
        $reading = TemperatureReading::create([
            'room_id' => $room->id,
            'refrigeration_system_id' => $refSystemId,
            'temperature' => $temperature,
            'recorded_by' => $userId,
        ]);

        $this->checkThresholds($room, $reading);

        \App\Jobs\PredictiveMaintenanceJob::dispatch();

        return $reading;
    }

    protected function checkThresholds(Room $room, TemperatureReading $reading)
    {
        if ($reading->temperature > $room->max_temperature) {
            event(new TemperatureThresholdExceeded($reading, $room->max_temperature, 'critical'));
        } elseif ($reading->temperature < $room->min_temperature) {
            event(new TemperatureThresholdExceeded($reading, $room->min_temperature, 'warning'));
        }
    }
}
