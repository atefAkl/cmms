<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@cmms.local'],
            [
                'name' => 'John Manager',
                'password' => Hash::make('password'),
            ]
        );
        if (!$manager->hasRole('Manager')) {
            $manager->assignRole('Manager');
        }

        // Generate Maintenance Officer User
        $officer = User::firstOrCreate(
            ['email' => 'officer@cmms.local'],
            [
                'name' => 'Mike Officer',
                'password' => Hash::make('password'),
            ]
        );
        if (!$officer->hasRole('Maintenance Officer')) {
            $officer->assignRole('Maintenance Officer');
        }
    }
}
