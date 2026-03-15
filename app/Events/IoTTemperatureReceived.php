<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IoTTemperatureReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reading;

    /**
     * Create a new event instance.
     */
    public function __construct(\App\Models\TemperatureReading $reading)
    {
        $this->reading = $reading;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new \Illuminate\Broadcasting\Channel('iot-monitoring'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->reading->id,
            'room_id' => $this->reading->room_id,
            'refrigeration_system_id' => $this->reading->refrigeration_system_id,
            'temperature' => $this->reading->temperature,
            'recorded_at' => $this->reading->recorded_at->toIso8601String(),
            'status' => $this->reading->room->status ?? 'active',
        ];
    }
}
