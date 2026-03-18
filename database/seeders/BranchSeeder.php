<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Branch::updateOrCreate(
            ['slug' => 'main-branch'],
            [
                'name' => 'Main Branch',
                'address' => '123 HQ Street, City',
                'phone' => '+123456789',
                'email' => 'hq@example.com',
                'website' => 'https://example.com',
                'logo' => 'logo.png',
                'favicon' => 'favicon.ico',
                'timezone' => 'UTC',
                'currency' => 'USD',
                'language' => 'en',
                'date_format' => 'Y-m-d',
                'time_format' => 'H:i:s',
                'date_time_format' => 'Y-m-d H:i:s',
            ]
        );
    }
}
