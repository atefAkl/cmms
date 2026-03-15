<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Room;
use App\Models\Compressor;
use App\Models\Evaporator;
use App\Models\TemperatureReading;
use App\Models\Inspection;
use App\Models\MaintenanceTask;
use App\Models\User;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officer = User::role('Maintenance Officer')->first() ?? User::factory()->create();

        $rooms = collect();
        for ($i=1; $i<=3; $i++) {
            $rooms->push(Room::create([
                'name' => "Cold Room 0$i",
                'location' => "Warehouse A - Sector $i",
                'target_temperature' => -18.0,
                'min_temperature' => -20.0,
                'max_temperature' => -15.0,
            ]));
        }

        foreach ($rooms as $room) {
            Compressor::create(['room_id' => $room->id, 'name' => "Comp {$room->name}-1", 'status' => 'active']);
            Compressor::create(['room_id' => $room->id, 'name' => "Comp {$room->name}-2", 'status' => 'active']);
            Evaporator::create(['room_id' => $room->id, 'fan_count' => 4, 'heater_count' => 2, 'status' => 'active']);
        }

        for ($i=0; $i<200; $i++) {
            TemperatureReading::create([
                'room_id' => $rooms->random()->id,
                'temperature' => rand(-210, -140) / 10,
                'recorded_by' => $officer->id,
                'recorded_at' => now()->subMinutes(rand(1, 10000)),
            ]);
        }

        for ($i=0; $i<10; $i++) {
            Inspection::create([
                'room_id' => $rooms->random()->id,
                'inspector_id' => $officer->id,
                'date' => now()->subDays(rand(1, 10)),
                'result' => 'pass'
            ]);
        }

        for ($i=0; $i<10; $i++) {
            MaintenanceTask::create([
                'room_id' => $rooms->random()->id,
                'issue_description' => "Routine maintenance checks and filter replacements #$i",
                'technician_id' => $officer->id,
                'status' => 'diagnosed',
                'cost' => rand(50, 500)
            ]);
        }
    }
}
