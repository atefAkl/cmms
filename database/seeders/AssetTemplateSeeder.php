<?php

namespace Database\Seeders;

use App\Models\AssetTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevent duplicate seeding
        if (AssetTemplate::count() > 0) {
            return;
        }

        // 1. Compressor
        $compressor = AssetTemplate::create([
            'name' => 'Compressor',
            'type' => 'compressor',
            'metadata' => ['is_repeatable' => true]
        ]);
        
        $compressor->children()->createMany([
            ['name' => 'Motor', 'type' => 'motor'],
            ['name' => 'Temperature Sensor', 'type' => 'sensor'],
            ['name' => 'Cooling Fan', 'type' => 'fan'],
        ]);

        // 2. Evaporator
        $evaporator = AssetTemplate::create([
            'name' => 'Evaporator',
            'type' => 'evaporator',
            'metadata' => ['is_repeatable' => true]
        ]);
        
        $evaporator->children()->createMany([
            ['name' => 'Air Fan', 'type' => 'fan'],
            ['name' => 'Defrost Heater', 'type' => 'heater'],
        ]);

        // 3. Control Panel
        AssetTemplate::create([
            'name' => 'Control Panel',
            'type' => 'control_panel',
            'metadata' => ['is_repeatable' => false]
        ]);

        // 4. Power Panel
        AssetTemplate::create([
            'name' => 'Power Panel',
            'type' => 'power_panel',
            'metadata' => ['is_repeatable' => false]
        ]);
    }
}
