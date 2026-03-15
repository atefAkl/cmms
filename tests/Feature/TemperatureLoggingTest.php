<?php
namespace Tests\Feature;
use App\Models\Room;
use App\Models\User;
use App\Services\TemperatureService;
use App\Events\TemperatureThresholdExceeded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TemperatureLoggingTest extends TestCase
{
    use RefreshDatabase;

    public function test_logging_temperature_triggers_event()
    {
        Event::fake();
        $user = User::factory()->create();
        $room = Room::create(['name' => 'Test Room', 'target_temperature' => -18, 'min_temperature' => -20, 'max_temperature' => -15]);
        
        $service = new TemperatureService();
        $service->logTemperature($room, -10, $user->id); // Too hot, threshold is max -15

        Event::assertDispatched(TemperatureThresholdExceeded::class);
    }
}
