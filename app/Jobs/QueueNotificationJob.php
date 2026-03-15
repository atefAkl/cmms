<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class QueueNotificationJob implements ShouldQueue
{
    use Queueable;

    public $alert;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Models\Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Illuminate\Support\Facades\Log::info("ALERT triggered for Room {$this->alert->room_id}. Temperature: {$this->alert->temperature}");
    }
}
