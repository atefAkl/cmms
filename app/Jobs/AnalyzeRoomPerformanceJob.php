<?php

namespace App\Jobs;

use App\Models\Room;
use App\Services\PredictiveAnalyticsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AnalyzeRoomPerformanceJob implements ShouldQueue
{
    use Queueable;

    protected $room;

    /**
     * Create a new job instance.
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Execute the job.
     */
    public function handle(PredictiveAnalyticsService $analyticsService): void
    {
        Log::info("Running predictive analytics for Room: {$this->room->name}");
        
        $analyticsService->analyzeGradient($this->room);
        $analyticsService->analyzeEfficiency($this->room);
    }
}
