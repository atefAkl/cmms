<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class LowStockAlertJob implements ShouldQueue
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
    public function handle(\App\Services\InventoryService $inventoryService): void
    {
        \Illuminate\Support\Facades\Log::info("LowStockAlertJob started processing.");
        $inventoryService->checkLowStockAndReorder();
    }
}
