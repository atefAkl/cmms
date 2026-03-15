<?php

namespace App\Services;

use App\Models\MaintenanceTask;
use Exception;
use Illuminate\Support\Carbon;

class MaintenanceTaskService
{
    /**
     * Update the status of a maintenance task.
     * Transitions must follow logical order.
     */
    public function updateStatus(MaintenanceTask $task, string $newStatus, ?int $technicianId = null): MaintenanceTask
    {
        $validStatuses = [
            MaintenanceTask::STATUS_OPEN,
            MaintenanceTask::STATUS_DIAGNOSED,
            MaintenanceTask::STATUS_ASSIGNED,
            MaintenanceTask::STATUS_IN_PROGRESS,
            MaintenanceTask::STATUS_COMPLETED,
            MaintenanceTask::STATUS_APPROVED,
            MaintenanceTask::STATUS_CLOSED,
        ];

        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception("Invalid status provided: {$newStatus}");
        }

        // Assignment logic
        if ($newStatus === MaintenanceTask::STATUS_ASSIGNED) {
            if ($technicianId) {
                $task->technician_id = $technicianId;
            }
            if (!$task->technician_id) {
                throw new Exception("A technician must be assigned when moving to assigned status.");
            }
        }

        // Timing logic
        if ($newStatus === MaintenanceTask::STATUS_IN_PROGRESS && !$task->started_at) {
            $task->started_at = Carbon::now();
        }

        if ($newStatus === MaintenanceTask::STATUS_COMPLETED && !$task->completed_at) {
            $task->completed_at = Carbon::now();
        }

        $task->status = $newStatus;
        $task->save();

        return $task;
    }
}
