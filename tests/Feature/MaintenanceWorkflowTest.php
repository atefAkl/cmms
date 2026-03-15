<?php

namespace Tests\Feature;

use App\Models\MaintenanceTask;
use App\Models\User;
use App\Services\MaintenanceTaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private MaintenanceTaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MaintenanceTaskService();
    }

    public function test_can_transition_to_assigned_with_technician()
    {
        $task = MaintenanceTask::factory()->create(['status' => MaintenanceTask::STATUS_OPEN]);
        $technician = User::factory()->create();

        $updatedTask = $this->service->updateStatus($task, MaintenanceTask::STATUS_ASSIGNED, $technician->id);

        $this->assertEquals(MaintenanceTask::STATUS_ASSIGNED, $updatedTask->status);
        $this->assertEquals($technician->id, $updatedTask->technician_id);
    }

    public function test_cannot_transition_to_assigned_without_technician()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("A technician must be assigned when moving to assigned status.");

        $task = MaintenanceTask::factory()->create(['status' => MaintenanceTask::STATUS_OPEN, 'technician_id' => null]);
        
        $this->service->updateStatus($task, MaintenanceTask::STATUS_ASSIGNED);
    }

    public function test_in_progress_sets_started_at()
    {
        $technician = User::factory()->create();
        $task = MaintenanceTask::factory()->create([
            'status' => MaintenanceTask::STATUS_ASSIGNED,
            'technician_id' => $technician->id
        ]);
        
        $updatedTask = $this->service->updateStatus($task, MaintenanceTask::STATUS_IN_PROGRESS);

        $this->assertEquals(MaintenanceTask::STATUS_IN_PROGRESS, $updatedTask->status);
        $this->assertNotNull($updatedTask->started_at);
    }
}
