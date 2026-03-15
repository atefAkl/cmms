<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PredictiveMaintenanceJob implements ShouldQueue
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
    public function handle(\App\Services\AiPredictiveService $predictiveService): void
    {
        $rooms = \App\Models\Room::all();
        foreach ($rooms as $room) {
            $predictiveService->analyzeRoomGradients($room);
        }
    }
}
