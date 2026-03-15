<?php

namespace Database\Factories;

use App\Models\MaintenanceTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaintenanceTask>
 */
class MaintenanceTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_id' => \App\Models\Room::factory(),
            'issue_description' => fake()->sentence(),
            'status' => \App\Models\MaintenanceTask::STATUS_OPEN,
            'technician_id' => null,
            'cost' => 0,
        ];
    }
}
