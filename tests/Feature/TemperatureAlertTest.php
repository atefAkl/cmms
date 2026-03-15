<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\TemperatureReading;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TemperatureAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_submit_temperature_reading()
    {
        $room = Room::factory()->create();

        $response = $this->postJson('/api/temperature-readings', [
            'room_id' => $room->id,
            'temperature' => -18.5,
            'humidity' => 50,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('temperature_readings', [
            'room_id' => $room->id,
            'temperature' => -18.5,
        ]);
    }

    public function test_rejects_invalid_room()
    {
        $response = $this->postJson('/api/temperature-readings', [
            'room_id' => 999,
            'temperature' => -18.5,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['room_id']);
    }
}
