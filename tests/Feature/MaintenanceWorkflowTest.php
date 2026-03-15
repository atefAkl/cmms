<?php
namespace Tests\Feature;
use App\Models\Room;
use App\Models\User;
use App\Models\MaintenanceTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_maintenance_workflow_status()
    {
        $user = User::factory()->create();
        $room = Room::create(['name' => 'Test Room', 'target_temperature' => -18, 'min_temperature' => -20, 'max_temperature' => -15]);

        $task = MaintenanceTask::create([
            'room_id' => $room->id,
            'issue_description' => 'Fix compressor',
            'status' => 'open'
        ]);

        $this->assertEquals('open', $task->status);
        $task->update(['status' => 'resolved']);
        $this->assertEquals('resolved', $task->fresh()->status);
    }
}
