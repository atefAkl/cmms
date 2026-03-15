<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Jobs\AnalyzeRoomPerformanceJob;
use Illuminate\Console\Command;

class RunAnalyticsCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trigger predictive analytics for all active refrigeration rooms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rooms = Room::all(); // Could filter by active systems if needed

        $this->info("Dispatching analytics jobs for {$rooms->count()} rooms...");

        foreach ($rooms as $room) {
            AnalyzeRoomPerformanceJob::dispatch($room);
        }

        $this->info("Jobs dispatched successfully.");
    }
}
