<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TemperatureThresholdExceeded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reading;
    public $threshold;
    public $severity;

    /**
     * Create a new event instance.
     */
    public function __construct(\App\Models\TemperatureReading $reading, $threshold, $severity = 'warning')
    {
        $this->reading = $reading;
        $this->threshold = $threshold;
        $this->severity = $severity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
