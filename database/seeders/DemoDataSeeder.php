<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Room;
use App\Models\RefrigerationSystem;
use App\Models\Asset;
use App\Models\TemperatureReading;
use App\Models\Inspection;
use App\Models\MaintenanceTask;
use App\Models\PmSchedule;
use App\Models\PmTask;
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
        for ($i = 1; $i <= 3; $i++) {
            $rooms->push(Room::create([
                'name' => "Cold Room 0$i",
                'slug' => "cold-room-0$i",
                'status' => 'running',
                'is_active' => true,
                'created_by' => $officer->id,
                'updated_by' => $officer->id,
            ]));
        }

        foreach ($rooms as $room) {
            $system = RefrigerationSystem::create([
                'room_id' => $room->id,
                'name' => "Ref System - {$room->name}",
                'status' => 'active',
                'installed_at' => now()->subYears(rand(1, 5)),
            ]);

            // Create Compressor Hierarchy
            $compressor = Asset::create([
                'refrigeration_system_id' => $system->id,
                'name' => "Main Compressor 01",
                'type' => 'compressor',
                'status' => 'active',
                'manufacturer' => 'Bitzer',
                'model' => '6HE-28Y',
            ]);

            Asset::create([
                'refrigeration_system_id' => $system->id,
                'parent_id' => $compressor->id,
                'name' => 'Drive Motor',
                'type' => 'motor',
                'status' => 'active',
            ]);

            Asset::create([
                'refrigeration_system_id' => $system->id,
                'parent_id' => $compressor->id,
                'name' => 'Oil Pressure Sensor',
                'type' => 'sensor',
                'status' => 'active',
            ]);

            Asset::create([
                'refrigeration_system_id' => $system->id,
                'parent_id' => $compressor->id,
                'name' => 'Condensing Fan 1',
                'type' => 'fan',
                'status' => 'active',
            ]);

            // Create Evaporator Hierarchy
            $evaporator = Asset::create([
                'refrigeration_system_id' => $system->id,
                'name' => "Evaporator Unit 01",
                'type' => 'evaporator',
                'status' => 'active',
                'manufacturer' => 'Guntner',
            ]);

            Asset::create([
                'refrigeration_system_id' => $system->id,
                'parent_id' => $evaporator->id,
                'name' => 'Air Fan 1',
                'type' => 'fan',
                'status' => 'active',
            ]);

            Asset::create([
                'refrigeration_system_id' => $system->id,
                'parent_id' => $evaporator->id,
                'name' => 'Defrost Heater',
                'type' => 'heater',
                'status' => 'active',
            ]);

            // Temp Readings
            for ($i=0; $i<10; $i++) {
                TemperatureReading::create([
                    'room_id' => $room->id,
                    'refrigeration_system_id' => $system->id,
                    'temperature' => rand(-210, -140) / 10,
                    'recorded_by' => $officer->id,
                    'recorded_at' => now()->subMinutes($i * 60),
                ]);
            }

            // PM Schedules
            $schedule = PmSchedule::create([
                'title' => "Quarterly Maintenance - {$system->name}",
                'equipment_type' => Asset::class,
                'equipment_id' => $compressor->id,
                'description' => "Check compressors and evaporators",
                'frequency_type' => PmSchedule::FREQUENCY_QUARTERLY,
                'frequency_value' => 1,
                'next_due' => now()->addMonths(3),
                'created_by' => $officer->id,
            ]);

            PmTask::create([
                'pm_schedule_id' => $schedule->id,
                'scheduled_date' => now()->addDays(7),
                'status' => PmTask::STATUS_PENDING,
            ]);
        }

        // Random Inspections
        for ($i=0; $i<5; $i++) {
            Inspection::create([
                'room_id' => $rooms->random()->id,
                'asset_id' => Asset::where('type', 'compressor')->get()->random()->id,
                'inspector_id' => $officer->id,
                'technician_id' => $officer->id,
                'date' => now()->subDays(rand(1, 10)),
                'scheduled_date' => now()->subDays(rand(1, 10)),
                'result' => 'pass'
            ]);
        }

        // Random Maintenance Tasks
        for ($i=0; $i<5; $i++) {
            $asset = Asset::all()->random();
            MaintenanceTask::create([
                'room_id' => $rooms->random()->id,
                'refrigeration_system_id' => $asset->refrigeration_system_id,
                'asset_id' => $asset->id,
                'issue_description' => "Vibration detected on motor #$i",
                'technician_id' => $officer->id,
                'status' => MaintenanceTask::STATUS_DIAGNOSED,
                'maintenance_type' => MaintenanceTask::TYPE_CORRECTIVE,
                'cost' => rand(50, 500)
            ]);
        }
    }
}
