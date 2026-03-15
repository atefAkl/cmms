<?php

namespace Database\Factories;

use App\Models\PmSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PmSchedule>
 */
class PmScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),
            'interval_days' => 30,
            'priority' => 'medium',
            'last_performed' => now()->subDays(30),
            'next_due' => now(),
            'estimated_duration' => 60,
        ];
    }
}
