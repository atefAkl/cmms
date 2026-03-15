<?php

namespace App\Listeners;

use App\Events\TemperatureThresholdExceeded;
use App\Models\Alert;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TriggerTemperatureAlert implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TemperatureThresholdExceeded $event): void
    {
        Alert::create([
            'room_id' => $event->reading->room_id,
            'temperature' => $event->reading->temperature,
            'threshold' => $event->threshold,
            'severity' => $event->severity,
            'status' => 'open'
        ]);
    }
}
