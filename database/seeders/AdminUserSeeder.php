<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update primary admin (login uses username)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('@Password1'),
                'role' => 'Admin',
            ]
        );

        // Also create a backup admin user with different credentials
        User::updateOrCreate(
            ['username' => 'admin2'],
            [
                'email' => 'admin@rainfall.com',
                'password' => Hash::make('Admin123!'),
                'role' => 'Admin',
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Username: admin | Password: @Password1');
        $this->command->info('Username: admin2 | Password: Admin123!');
    }
}


