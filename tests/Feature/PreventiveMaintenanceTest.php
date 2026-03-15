<?php

namespace Tests\Feature;

use App\Jobs\GeneratePreventiveMaintenanceTasks;
use App\Models\PmSchedule;
use App\Models\Room;
use App\Models\MaintenanceTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PreventiveMaintenanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_generates_maintenance_task_when_due()
    {
        Carbon::setTestNow('2026-06-01');

        $room = Room::factory()->create();
        $schedule = PmSchedule::factory()->create([
            'equipment_type' => Room::class,
            'equipment_id' => $room->id,
            'description' => 'Monthly Calibration',
            'interval_days' => 30,
            'next_due' => '2026-06-01',
            'priority' => 'high'
        ]);

        $job = new GeneratePreventiveMaintenanceTasks();
        $job->handle();

        $this->assertDatabaseHas('maintenance_tasks', [
            'room_id' => $room->id,
            'status' => 'open',
        ]);

        $schedule->refresh();
        $this->assertEquals('2026-06-01', $schedule->last_performed->format('Y-m-d'));
        $this->assertEquals('2026-07-01', $schedule->next_due->format('Y-m-d'));
    }

    public function test_job_does_not_generate_task_if_not_due()
    {
        Carbon::setTestNow('2026-06-01');

        $room = Room::factory()->create();
        $schedule = PmSchedule::factory()->create([
            'equipment_type' => Room::class,
            'equipment_id' => $room->id,
            'next_due' => '2026-06-15', // Due in the future
        ]);

        $job = new GeneratePreventiveMaintenanceTasks();
        $job->handle();

        $this->assertDatabaseCount('maintenance_tasks', 0);
        $schedule->refresh();
        $this->assertEquals('2026-06-15', $schedule->next_due->format('Y-m-d'));
    }
}
